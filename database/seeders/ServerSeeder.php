<?php

namespace Database\Seeders;

use App\Models\Mod;
use App\Models\Server;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServerSeeder extends ModSeeder
{
    public function run(): void
    {
//        $server = new Server;
//        $server->name = '<Your amazing server name>';
//        $server->ip_address = '<ip address>';
//        $server->mission = 'dayzOffline.chernarusplus';
//        $server->fps_limit = 150;
//        $server->player_slots = 40;
//        $server->password_rcon = Str::random(20);
//        $server->password_admin = Str::random(20);
//        $server->cpu_count = 2;
//        $server->third_person_enabled = false;
//        $server->personal_light_enabled = false;
//        $server->time_day_speed = 6;
//        $server->time_night_speed = 2;
//
//        $server->save();
//
//        foreach ($this->mods as $mod => $prio) {
//            $server->mods()->attach($mod, ['enabled' => true]);
//
//            Mod::find($mod)->copyFileEdits($server);
//        }
    }
}
