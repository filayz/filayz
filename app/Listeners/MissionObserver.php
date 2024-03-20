<?php

namespace App\Listeners;

use App\Models\Mission;
use App\Server\XML\ProcessItemsXML;

class MissionObserver
{
    public function saving(Mission $mission)
    {
        (new ProcessItemsXML)("$mission->root_path/$mission->path_types_xml", $mission);
    }
}
