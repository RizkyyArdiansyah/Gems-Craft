<?php

namespace Database\Factories;

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
        return [
            'product_name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(50000, 1000000),
            'category' => $this->faker->randomElement([
                'Sepatu', 'Baju', 'Celana', 'Jaket', 'Aksesoris',
                'Tas', 'Topi', 'Jam Tangan', 'Kacamata', 'Batu Cincin'
            ]),
            'stock' => $this->faker->numberBetween(1, 50),
            'images' => $this->faker->imageUrl(300, 300, 'fashion', true, 'Product'), // Gambar fashion
        ];
    }
}
