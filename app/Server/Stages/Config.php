<?php

namespace App\Server\Stages;

use App\Models\Server;
use Illuminate\Support\Facades\View;
use League\Pipeline\StageInterface;

class Config implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        $settings = View::make('servers.settings', ['server' => $payload])->render();
        $mission = View::make('servers.mission', ['server' => $payload])->render();

        file_put_contents(
            $payload->path . '/serverDZ.cfg',
            <<<EOM
$settings

$mission
EOM

        );

        return $payload;
    }
}
