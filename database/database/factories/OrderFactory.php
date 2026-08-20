<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100000, 2000000);

        return [
            'user_id' => User::factory(),
            'code' => 'ORD-' . strtoupper(fake()->unique()->bothify('??######')),
            'shipping_name' => fake()->name(),
            'company_name' => null,
            'shipping_email' => fake()->safeEmail(),
            'shipping_phone' => fake()->numerify('08##########'),
            'shipping_address' => fake()->address(),
            'shipping_country' => 'Indonesia',
            'shipping_province' => 'Nusa Tenggara Barat',
            'shipping_city' => 'Kota Mataram',
            'shipping_district' => 'Cakranegara',
            'shipping_postal_code' => '83239',
            'shipping_method' => 'Free Local Shipping',
            'total_weight_kg' => 5,
            'total_product_price' => $subtotal,
            'shipping_price' => 0,
            'discount_amount' => 0,
            'grand_total' => $subtotal,
            'order_status' => 'new',
            'payment_status' => 'unpaid',
            'notes' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'processing',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
