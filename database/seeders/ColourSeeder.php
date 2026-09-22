<?php

namespace Database\Seeders;

use App\Models\Colour;
use App\Models\SizeChart;
use Illuminate\Database\Seeder;

class ColourSeeder extends Seeder
{
    public function run(): void
    {
        $colours = [
            ['name' => 'Black', 'slug' => 'black', 'hex' => '#101012'],
            ['name' => 'White', 'slug' => 'white', 'hex' => '#f4f2ee'],
            ['name' => 'Charcoal', 'slug' => 'charcoal', 'hex' => '#3d3d3f'],
            ['name' => 'Stone', 'slug' => 'stone', 'hex' => '#c9c1b4'],
            ['name' => 'Navy', 'slug' => 'navy', 'hex' => '#232a3a'],
            ['name' => 'Olive', 'slug' => 'olive', 'hex' => '#4a4a36'],
            ['name' => 'Burgundy', 'slug' => 'burgundy', 'hex' => '#5a2323'],
            ['name' => 'Grey', 'slug' => 'grey', 'hex' => '#8f8d89'],
            ['name' => 'Sand', 'slug' => 'sand', 'hex' => '#d8cfc0'],
        ];

        foreach ($colours as $colour) {
            Colour::updateOrCreate(['slug' => $colour['slug']], $colour);
        }

        SizeChart::updateOrCreate(['name' => 'Top Sizes'], [
            'category_type' => 'tops',
            'rows' => [
                ['size' => 'XS', 'chest' => '88–94', 'waist' => '74–80', 'length' => '66'],
                ['size' => 'S', 'chest' => '94–100', 'waist' => '80–86', 'length' => '68'],
                ['size' => 'M', 'chest' => '100–106', 'waist' => '86–92', 'length' => '70'],
                ['size' => 'L', 'chest' => '106–112', 'waist' => '92–98', 'length' => '72'],
                ['size' => 'XL', 'chest' => '112–118', 'waist' => '98–104', 'length' => '74'],
                ['size' => 'XXL', 'chest' => '118–124', 'waist' => '104–110', 'length' => '76'],
            ],
        ]);

        SizeChart::updateOrCreate(['name' => 'Bottom Sizes'], [
            'category_type' => 'bottoms',
            'rows' => [
                ['size' => '28', 'waist' => '71–74', 'hips' => '88–91', 'inside_leg' => '76'],
                ['size' => '30', 'waist' => '76–79', 'hips' => '93–96', 'inside_leg' => '77'],
                ['size' => '32', 'waist' => '81–84', 'hips' => '98–101', 'inside_leg' => '78'],
                ['size' => '34', 'waist' => '86–89', 'hips' => '103–106', 'inside_leg' => '80'],
                ['size' => '36', 'waist' => '91–94', 'hips' => '108–111', 'inside_leg' => '81'],
            ],
        ]);
    }
}