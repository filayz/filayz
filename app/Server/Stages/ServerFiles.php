<?php

namespace App\Server\Stages;

use App\Console\Commands\DownloadServerFilesCommand;
use App\Models\Server;
use Illuminate\Support\Facades\Artisan;
use League\Pipeline\StageInterface;

class ServerFiles implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        Artisan::call(DownloadServerFilesCommand::class, [
            'server' => $payload->id
        ]);

        return $payload;
    }
}
