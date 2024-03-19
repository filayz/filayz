<?php

namespace App\Server\Stages;

use App\Models\Server;
use Illuminate\Support\Facades\Storage;
use League\Pipeline\StageInterface;

class Directory implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        // base directory
        Storage::disk('servers')->makeDirectory($payload->id);

        // mods directory
        Storage::disk('servers')->makeDirectory("$payload->id/mods");

        // storage directory
        Storage::disk('servers')->makeDirectory("$payload->id/storage");

        // profiles directory
        Storage::disk('servers')->makeDirectory("$payload->id/profiles");

        return $payload;
    }
}
