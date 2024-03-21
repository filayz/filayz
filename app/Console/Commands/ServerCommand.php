<?php

namespace App\Console\Commands;

use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ServerCommand extends Command
{
    protected $signature = 'app:server {id}
        {--start : Starts the server}
        {--stop : Stops the server}
        {--status : Checks the server (default)}
    ';
    protected $description = 'Interacts with your DayZ server.';

    public function handle()
    {
        $id = $this->argument('id');

        /** @var Server $server */
        $server = Server::query()->findOrFail($id);

        match(true) {
            $this->option('start') && ! $server->isRunning => $this->start($server),
            $this->option('stop') && $server->isRunning => $this->stop($server),
            default => $this->status($server)
        };

        return 0;
    }

    protected function status(Server $server)
    {
        $this->output->definitionList([
            'Running' => $server->isRunning ? 'yes' : 'no',
        ],[
            'Name' => $server->name,
        ],[
            'Game port' => $server->port_game,
        ],[
            'Process ID' => $server->pid,
        ]);

        return match (true) {
            $server->isRunning && $this->confirm('Do you want to stop the server?') => $this->stop($server),
            ! $server->isRunning && $this->confirm('Do you want to start the server?') => $this->start($server),
            default => 0
        };
    }

    protected function start(Server $server)
    {
        $server->requested_boot_at = Carbon::now();
        $server->save();

        return $this->status($server);
    }

    protected function stop(Server $server)
    {
        $server->requested_exit_at = Carbon::now();
        $server->save();

        return $this->status($server);
    }
}
