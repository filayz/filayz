<?php

namespace App\Server\Stages;

use App\Models\Server;
use League\Pipeline\StageInterface;

class BattlEye implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        file_put_contents("$payload->path/battleye/BEServer_x64.cfg", <<<EOM
RConPassword $payload->password_rcon
RestrictRCon 1
EOM
);
        return $payload;
    }
}
