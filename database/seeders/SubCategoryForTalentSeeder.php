<?php

namespace Database\Seeders;

use App\Models\Stage;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubCategoryForTalentSeeder extends Seeder
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

        $category = Category::where('stage_id', $stage)->where('tags', 'talent')->first()->id;

        $subcategories = [
            0 => [
                'sub_category_name' => 'Mastery and Execution',
                'sub_category_percent' => '50',
            ],
            1 => [
                'sub_category_name' => 'Originality',
                'sub_category_percent' => '40',
            ],
            2 => [
                'sub_category_name' => 'Audience Impact',
                'sub_category_percent' => '10',
            ]
        ];

        foreach($subcategories as $data) {
            SubCategory::create([
                'sub_category_name' => $data['sub_category_name'],
                'sub_category_percent' => $data['sub_category_percent'],
                'category_id' => $category
            ]);
        }
    }
}
