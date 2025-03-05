<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ColorSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $colors = [
            ['name' => 'Bright Red-Pink', 'code' => '0xffFF2D5E'],
            ['name' => 'Hot Pink', 'code' => '0xffFF329B'],
            ['name' => 'Neon Magenta', 'code' => '0xffEC33F7'],
            ['name' => 'Purple', 'code' => '0xff9F4FFF'],
            ['name' => 'Indigo Blue', 'code' => '0xff5946F9'],
            ['name' => 'Vivid Blue', 'code' => '0xff0082FF'],
            ['name' => 'Sky Blue', 'code' => '0xff00A8EF'],
            ['name' => 'Deep Sky Blue', 'code' => '0xff00B2FF'],
            ['name' => 'Turquoise', 'code' => '0xff00BECA'],
            ['name' => 'Medium Sea Green', 'code' => '0xff00BC7C'],
        ];  

        foreach ($colors as $color) {
            \App\Models\Color::create($color);
        }
    }
}
