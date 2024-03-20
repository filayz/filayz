<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissionSeeder extends Seeder
{
    private array $missions = [
        'dayzOffline.chernarusplus' => 'Chernarus Plus',
        'empty.deerisle' => 'Deer Isle',
    ];

    public function run(): void
    {
        foreach ($this->missions as $path => $name) {
            DB::table('missions')->insert([
                'name' => $name,
                'path' => $path
            ]);
        }
    }
}
