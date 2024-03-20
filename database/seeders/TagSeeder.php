<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    protected array $tags = [
        'shelves', 'ground', 'floor'
    ];

    public function run(): void
    {
        foreach ($this->tags as $tag) {
            DB::table('tags')->insert(['name' => $tag]);
        }
    }
}
