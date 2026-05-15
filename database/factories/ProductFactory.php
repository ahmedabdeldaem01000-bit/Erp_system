<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        

        return [
            'name'=>fake()->name(),
            'image'=>fake()->word(),
            'price'=>fake()->numberBetween(100,200),
            'description'=>fake()->paragraph(),
            'quantity'=>fake()->randomNumber(),
          'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
