<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CheckoutPesananFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'province' => fake()->state(),
            'city' => fake()->city(),
            'courier' => fake()->randomElement(['JNE', 'TIKI', 'POS', 'SiCepat', 'GoSend']),
            'address' => fake()->address(),
            'total_cost' => fake()->numberBetween(50000, 2000000),
        ];
    }
}
