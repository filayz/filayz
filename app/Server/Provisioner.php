<?php

namespace App\Server;

use App\Models\Server;
use League\Pipeline\Pipeline;

class Provisioner
{
    public function __invoke(Server $server)
    {
        (new Pipeline)
            ->pipe(new Stages\Directory)
            ->pipe(new Stages\ServerFiles)
            ->pipe(new Stages\LinkMods)
            ->pipe(new Stages\Bikeys)
            ->pipe(new Stages\Mission)
            ->pipe(new Stages\Config)
            ->pipe(new Stages\BattlEye)
            ->pipe(new Stages\TypesXml)
            ->pipe(new Stages\ProfileFiles)
            ->pipe(new Stages\VPPAdmin)
            ->process($server);
    }
}
