<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageCount = $this->faker->numberBetween(1, 4);
        for ($i = 1; $i <= $imageCount; $i++) {
            $image = rand(1, 4);
            $images[] = "products/example{$image}.jpg";
        }

        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => $name = $this->faker->sentence(),
            'slug' => str($name)->slug(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(10, 2000) * 1000,
            'stock' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->numberBetween(1, 100),
            'weight_unit' => $this->faker->randomElement(['gram', 'kg']),
            'length' => $this->faker->numberBetween(1, 100),
            'width' => $this->faker->numberBetween(1, 100),
            'height' => $this->faker->numberBetween(1, 100),
            'dimension_unit' => $this->faker->randomElement(['cm', 'm']),
            'images' => $images,
            'is_active' => $this->faker->boolean(),
        ];
    }
}
