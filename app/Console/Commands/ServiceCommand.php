<?php

namespace App\Console\Commands;

use App\Models\Mod;
use App\Models\Server;
use App\Server\Provisioner;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Process\InvokedProcess;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class ServiceCommand extends Command implements Isolatable
{
    protected $signature = 'app:server:service {--fake : Do not start server}';
    protected $description = 'Daemonizes the servers';

    protected array|InvokedProcess $children = [];

    public function handle()
    {
        $this->info("Starting service.");

        while(true) {
            $timer = microtime(true);

            $halt = cache()->has('server-service-halt');

            Server::query()->each(function (Server $server) use ($halt) {
                match (true) {
                    $halt => $this->stop($server),
                    $server->pid && ! $server->isRunning => $this->cleanUp($server) && $this->boot($server),
                    $server->requested_boot_at && ! $server->isRunning => $this->boot($server),
                    $server->isRunning && $server->requested_restart_at => $this->restart($server),
                    $server->isRunning && $server->requested_exit_at => $this->stop($server, true),
                    $server->isRunning => $this->liveness($server),
                    default => 0
                };
            });

            if ($halt) {
                cache()->forget('server-service-halt');

                $this->info('Servers halted, exiting');

                break;
            }

            if (microtime(true) - $timer < 1) sleep(5);
        }

        return 0;
    }

    private function cleanUp(Server $server)
    {
        $this->info("Cleaning up $server->id : $server->name with pid $server->pid.");

        if ($server->pid) unset($this->children[$server->pid]);

        $server->pid = null;
        $server->booted_at = null;
        $server->requested_exit_at = null;
        $server->requested_restart_at = null;
        $server->exited_at = $server->exited_at ?? Carbon::now();

        $server->save();

        return true;
    }

    private function boot(Server $server)
    {
        $this->info("Booting up $server->id : $server->name on port $server->port_game.");

        /** @var Provisioner $provision */
        $provision = resolve(Provisioner::class);

        // Guarantee all prerequisites are met, like server files and mods.
        $provision($server);

        $this->info("Provisioner completed. Booting ...");

        $mods = $server
            ->mods()
            ->orderBy('load_priority', 'asc')
            ->wherePivot('enabled', true)
            ->get()
            ->map(fn (Mod $mod) => "mods/$mod->id")
            ->join(';');

        $args = [
            './DayZServer', "-config=$server->path/serverDZ.cfg",
            "-port=$server->port_game",
            "-storage=$server->path/storage/",
            "-profiles=$server->path/profiles/",
            '-doLogs', '-adminLog', '-filePatching',
            "-cpuCount=$server->cpu_count", "-limitFPS=$server->fps_limit",
        ];

        if ($mods) {
            $args[] = "-mod=$mods";
        }

        $process = Process::command($args)
            ->env(['CWD' => $server->path])
            ->path($server->path)
            ->idleTimeout(60 * 10)
            ->forever();

        if ($this->getOutput()->isVerbose()) {
            $this->info("Process defined, starting ...");

            $cmd = join(' ', $process->command);

            $this->comment("Using command: $cmd.");

            unset($cmd);
        }

        if ($this->option('fake')) {
            $this->output->comment('Not executing command.');

            $server->pid = 0;

            $server->save();

            return true;
        }

        $process = $process->start(
            output: fn(string $type, string $output) => $this->handleOutput($server, $output)
        );

        $server->pid = $process->id();

        $this->children[$server->pid] = $process;

        $this->info("Process started, pid: $server->pid.");

        $server->save();

        return true;
    }

    private function stop(Server $server, bool $terminate = false): bool
    {
        $this->info("Stopping $server->id : $server->name with pid $server->pid.");

        /** @var InvokedProcess $process */
        if (($process = Arr::get($this->children, $server->pid)) && $process->running()) {
            $process->signal(3);
        }

        if ($terminate) {
            $server->requested_boot_at = null;
        }

        $this->cleanUp($server);

        return true;
    }

    private function liveness(Server $server): bool
    {
        // Faked process.
        if ($server->pid === 0) return true;

        /** @var InvokedProcess $process */
        $process = Arr::get($this->children, $server->pid);

        if (! $process) {
            $this->info("Liveness for $server->id $server->name found no child process.");

            return $this->cleanUp($server);
        }

        if (! $process->running()) {
            $this->info("Liveness for $server->id $server->name noticed process killed unexpectedly.");

            return $this->cleanUp($server);
        }

        return true;
    }

    protected function restart(Server $server): bool
    {
        $this->stop($server);

        return $this->boot($server);
    }

    public function handleOutput(Server $server, string $output)
    {
        if (Str::contains($output, 'No world with name ')) {
            throw new \InvalidArgumentException('World cannot be loaded, confirm keys and mods.');
        }

        $disallowed = [
            'Convex', 'No components', 'Way too much components',
            'contains water texture however', 'DamageSystem Warning',
            'No entry', 'Unsafe down-casting', 'FIX-ME',
            'causing search overtime', 'not in localization table',
            'Localization not present', 'Attempt to register duplicate exclusion group'
        ];

        if (Str::contains($output, $disallowed)) return;

        $this->log($server, $output);

        if($this->getOutput()->isVeryVerbose()) {
            $this->output->comment("Process for $server->id generated output: $output");
        }
    }

    protected function log(Server $server, string $output): void
    {
        // Identify time
        preg_match(
            '~^(?<hours>[0-9]{2}):(?<minutes>[0-9]{2}):(?<seconds>[0-9]{2})(?<type>[^:]+)?(:)?~',
            $output,
            $m
        );

        $created_at = Carbon::now();

        $created_at->setTime(
            $m['hours'],
            $m['minutes'],
            $m['seconds']
        );

        $type = 'log';

        if (Str::contains($m['type'], ['error', 'warning'])) {
            $type = 'error';
        }

        $output = Str::after($output, $m[0]);

        // Replace partial timestamps that are still showing in the logs.
        $output = preg_replace('~^[0-9]{2}:[0-9]{2} ~', '', $output);

        $output = trim($output);

        if ($server
            ->logs()
            ->where('mute', true)
            ->where('contents', 'like', "%$output%")
            ->exists()) {
            // we ignore muted duplicates

            return;
        }

        $server->logs()->newModelInstance([
            'server_id' => $server->id,
            'contents' => $output,
            'type' => $type
        ])->save();
    }
}
