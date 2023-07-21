<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => rand(2,3),
            'cname' => $this->faker->name(),
            'cemail' => $this->faker->email(),
            'cnumber' => $this->faker->phoneNumber(),
            'cstate' => $this->faker->city(),
            'caddress' => $this->faker->address(),
            'cperson' => $this->faker->name(),
            'cpostal_code' => $this->faker->postcode(),
        ];
    }
}
