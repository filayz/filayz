<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ModSeeder::class);
        $this->call(ModFileSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ServerSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TierSeeder::class);
        $this->call(MissionSeeder::class);
        $this->call(TagSeeder::class);
    }
}
