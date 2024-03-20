<?php

namespace App\Server\Stages;

use App\Models\Server;
use Illuminate\Support\Facades\Process;
use League\Pipeline\StageInterface;

class Mission implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        $missionPath = config('filesystems.disks.missions.root') . "/{$payload->mission->path}";

        if (is_link("$payload->path/mpmissions/")) {
            // Remove any potential symlinks.
            Process::run("rm -f $missionPath");
        }

        // We need to copy this directory.
        Process::timeout(60 * 5)->run("cp -R -f $missionPath $payload->path/mpmissions/")->throw();

        return $payload;
    }
}
