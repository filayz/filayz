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
        'Medic', 'Lunapark', 'Farm'
    ];

    public function run(): void
    {
        foreach ($this->usages as $usage) {
            if (DB::table('areas')->where('name', $usage)->exists()) continue;

            DB::table('areas')->insert(
                values: ['name' => $usage]
            );
        }
    }
}
