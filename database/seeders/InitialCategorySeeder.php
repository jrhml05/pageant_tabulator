<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InitialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get active stage
        $stage = Stage::where('is_active', 1)->first()->id;

        $categories = [
            0 => [
                'category_name' => 'Beauty',
                'percent' => '35',
            ],
            1 => [
                'category_name' => 'Talent',
                'percent' => '20',
            ],
            2 => [
                'category_name' => 'Intelligence',
                'percent' => '25',
            ],
            3 => [
                'category_name' => 'Poise',
                'percent' => '20',
            ]
        ];

        foreach ($categories as $category) {
            //initialize zero score to scores table based on stage, categories, judges and barangays
            Category::create([
                'stage_id' => $stage,
                'category_name' => $category['category_name'],
                'percent' => $category['percent'],
                'tags' => str_replace(' ', '-', strtolower($category['category_name'])),
            ]);
        }
    }
}
