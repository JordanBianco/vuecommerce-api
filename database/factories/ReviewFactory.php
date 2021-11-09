<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::all()->random()->id,
            'title' => $this->faker->sentence(),  
            'content' => $this->faker->paragraph(),  
            'rating' => random_int(1, 5),
        ];
    }
}