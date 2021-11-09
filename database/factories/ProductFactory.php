<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->sentence(3);
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->faker->paragraph(3),
            'height' => random_int(50, 120),
            'weight' => random_int(200, 50000),
            'price' => random_int(200, 4000),
            'image_path' => 'https://i.pickadummy.com/600x400'
        ];
    }
}
