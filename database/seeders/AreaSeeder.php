<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    private array $usages = [
        'Military', 'Police',
        'Town', 'Office', 'School', 'Village',
        'Firefighter', 'Hunting', 'Coast',
        'Industrial', 'Underground', 'Crypt',
        'Medic', 'Lunapark'
    ];

    public function run(): void
    {
        foreach ($this->usages as $usage) {
            DB::table('areas')->insert(['name' => $usage]);
        }
    }
}
