<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('COUPON###')),
            'type' => 'fixed',
            'value' => 10000,
            'min_spend' => 0,
            'is_active' => true,
            'expiry_date' => now()->addMonth(),
        ];
    }

    public function percent(int $value = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percent',
            'value' => $value,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => now()->subDay(),
        ]);
    }

    public function minSpend(float $minSpend): static
    {
        return $this->state(fn (array $attributes) => [
            'min_spend' => $minSpend,
        ]);
    }
}
