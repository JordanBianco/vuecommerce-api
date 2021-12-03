<?php

namespace Database\Factories;

use App\Models\User;
use Database\Factories\Helper\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_number' => $this->faker->randomNumber(),
            'user_id' => FactoryHelper::getRandomModelId(User::class),
            'total' => $this->faker->randomNumber(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'province' => $this->faker->word(),
            'address' => $this->faker->address(),
            'zipcode' => $this->faker->randomNumber(5, true),
            'phone' => $this->faker->phoneNumber(),
            'notes' => null,
            'archived_at' => null
        ];
    }
}
