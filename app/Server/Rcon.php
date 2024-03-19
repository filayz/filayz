<?php

namespace App\Server;

use App\Models\Server;
use xPaw\SourceQuery\SourceQuery;

class Rcon
{
    protected SourceQuery $query;

    public function __construct(protected Server $server)
    {
        $this->query = new SourceQuery();

        $this->query->SetRconPassword($server->password_rcon);

        $this->query->Connect(
            $server->ip_address,
            $server->port_rcon
        );
    }

    public function info()
    {
        return $this->query->GetInfo();
    }

    public function players()
    {
        return $this->query->GetPlayers();
    }

    public function rules()
    {
        return $this->query->GetRules();
    }
}
