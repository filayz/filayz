<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TierSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i <= 18; $i++) {

            DB::table('tiers')->insert(['name' => "Tier$i", 'description' => "Tier $i"]);
        }
    }
}
