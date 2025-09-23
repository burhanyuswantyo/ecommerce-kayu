<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Kayu Jati',
            'Kayu Mahoni',
            'Kayu Meranti',
            'Kayu Kamper',
            'Kayu Pinus',
            'Kayu Sengon',
            'Plywood (Triplek)',
            'MDF (Medium Density Fiberboard)',
            'Blockboard',
            'Multiplek',
            'Partikel Board',
            'Finger Joint',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => str($category)->slug(),
            ]);
        }
    }
}
