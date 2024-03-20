<?php

namespace App\Console\Commands;

use App\Models\Mod;
use Illuminate\Console\Command;

class TouchModsCommand extends Command
{
    protected $signature = 'app:mods:touch';

    public function handle()
    {
        Mod::query()
            ->each(function (Mod $mod) {
                $mod->touch();
            });
    }
}
