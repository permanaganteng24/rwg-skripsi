<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => '<p>Produk kayu jati untuk kebutuhan testing.</p>',
            'price' => fake()->numberBetween(500000, 5000000),
            'weight_kg' => fake()->randomFloat(2, 5, 80),
            'length_cm' => fake()->randomFloat(2, 40, 200),
            'width_cm' => fake()->randomFloat(2, 30, 120),
            'height_cm' => fake()->randomFloat(2, 30, 120),
            'material' => 'Solid Teak Wood',
            'finishing' => 'Natural',
            'color' => 'Brown',
            'stock' => fake()->numberBetween(1, 20),
            'availability' => 'ready',
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
