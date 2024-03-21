<?php

namespace App\Console\Commands;

use App\Models\Mod;
use App\Server\Steam\Process;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadModsCommand extends Command
{
    protected $signature = 'app:mods:download';
    protected $description = 'Downloads and updates all enabled mods.';

    public function handle()
    {
        $downloadText = Mod::query()
            ->select('id', 'name')
            ->get()
            ->map(fn ($mod) => "workshop_download_item 221100 {$mod->id} // {$mod->name}")
            ->join("\n");

        $directory = config('filesystems.disks.mods.root');

        $username = config('steam.username') ?? 'anonymous';
        $password = config('steam.password');

        $txt = <<<EOM
@ShutdownOnFailedCommand 1

force_install_dir $directory

login $username $password

$downloadText

quit
EOM;

        Storage::disk('mods')->put('dayz.txt', $txt);

        $process = Process::make([
            'steamcmd', '+runscript', "$directory/dayz.txt"
        ], $directory)
            ->timeout(60 * 10)
            ->run()
            ->throw();

        return $process->exitCode();
    }
}
