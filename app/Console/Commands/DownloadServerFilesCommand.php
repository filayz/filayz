<?php

namespace App\Console\Commands;

use App\Models\Mod;
use App\Models\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class DownloadServerFilesCommand extends Command
{
    protected $signature = 'app:server:download {server}';
    protected $description = 'Downloads and updates server files for server.';

    public function handle()
    {
        $id = $this->argument('server');

        /** @var Server $server */
        $server = Server::query()->findOrFail($id);

        $directory = $server->path;

        $username = config('steam.username') ?? 'anonymous';
        $password = config('steam.password');

        $txt = <<<EOM
@ShutdownOnFailedCommand 1

force_install_dir $directory

login $username $password

app_update 223350 validate

quit
EOM;

        Storage::disk('servers')->put("$id/dayz.txt", $txt);

        $process = Process::forever()
            ->run("steamcmd +runscript $directory/dayz.txt")
            ->throw();

        return $process->exitCode();
    }
}
