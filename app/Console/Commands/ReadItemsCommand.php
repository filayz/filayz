<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Mod;
use Illuminate\Console\Command;

class ReadItemsCommand extends Command
{
    protected $signature = 'app:items:read';

    public function handle()
    {
        Mission::query()
            ->each(function( Mission $mission) {
                $mission->touch();
            });

        Mod::query()
            ->each(function (Mod $mod) {
                $mod->touch();
            });
    }
}
