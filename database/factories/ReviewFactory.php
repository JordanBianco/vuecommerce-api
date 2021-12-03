<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Helper\FactoryHelper;

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
            'user_id' => FactoryHelper::getRandomModelId(User::class),
            'product_id' => FactoryHelper::getRandomModelId(Product::class),
            'content' => $this->faker->paragraph(),  
            'rating' => random_int(1, 5),
        ];
    }
}
