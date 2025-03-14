<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuleSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Brown', 'code' => '#A52A2A'],
            ['name' => 'Cyan', 'code' => '#00FFFF'],
            ['name' => 'Magenta', 'code' => '#FF00FF'],
        ];

        foreach ($colors as $color) {
            \App\Models\Rule::create($color);
        }
    }
}
