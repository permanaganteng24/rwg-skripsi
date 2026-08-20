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
        $name = ucfirst(fake()->unique()->words(3, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 999999),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(100000, 5000000),

            'weight_kg' => fake()->randomFloat(2, 1, 50),
            'length_cm' => fake()->randomFloat(2, 20, 200),
            'width_cm' => fake()->randomFloat(2, 20, 200),
            'height_cm' => fake()->randomFloat(2, 20, 200),
            'material' => fake()->randomElement(['Jati', 'Mahoni', 'Mindi', 'Sonokeling']),
            'finishing' => fake()->randomElement(['Natural', 'Duco', 'Melamine']),
            'color' => fake()->safeColorName(),

            'stock' => 10,
            'availability' => 'ready',
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    /**
     * Product that is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'availability' => 'out_of_stock',
        ]);
    }

    /**
     * Product that is inactive / hidden from customers.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
