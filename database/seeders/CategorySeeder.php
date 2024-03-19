<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    private array $usages = [
        'weapons', 'tools',
        'clothes', 'food', 'containers', 'industrialfood',
        'lootdispatch', 'explosives'
    ];

    public function run(): void
    {
        foreach ($this->usages as $usage) {
            DB::table('categories')->insert(['name' => $usage]);
        }
    }
}
