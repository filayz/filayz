<?php

namespace App\Server\Stages;

use App\Models\Mod;
use App\Models\Server;
use Illuminate\Support\Facades\Process;
use League\Pipeline\StageInterface;

class LinkMods implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        $serverDir = $payload->path;

        // Clear out the previous symlinks.
        Process::run("rm -f $serverDir/mods/*");

        $payload
            ->mods()
            ->select('mods.id')
            ->wherePivot('enabled', true)
            ->each(function (Mod $mod) use ($serverDir) {
                // Create a symlink for each configured mod.
                // Relative symlink do seem to work, absolute do not!
                Process::path("$serverDir/mods")
                    ->run("ln -sf ../../../mods/steamapps/workshop/content/221100/$mod->id ./$mod->id")
                    ->throw();
            });

        return $payload;
    }
}
