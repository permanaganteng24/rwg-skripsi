<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(1000000, 5000000);

        return [
            'user_id' => User::factory(),
            'code' => 'ORD-' . strtoupper(Str::random(8)),
            'shipping_name' => fake()->name(),
            'company_name' => null,
            'shipping_phone' => '08123456789',
            'shipping_email' => fake()->safeEmail(),
            'shipping_address' => fake()->streetAddress(),
            'shipping_country' => 'Indonesia',
            'shipping_province' => 'Nusa Tenggara Barat',
            'shipping_city' => 'KOTA MATARAM',
            'shipping_district' => 'MATARAM',
            'shipping_postal_code' => '83115',
            'shipping_method' => 'Free Local Shipping',
            'shipping_courier' => null,
            'tracking_number' => null,
            'total_weight_kg' => 10,
            'total_product_price' => $subtotal,
            'shipping_price' => 0,
            'discount_amount' => 0,
            'grand_total' => $subtotal,
            'order_status' => 'waiting_payment',
            'payment_status' => 'unpaid',
            'payment_method' => null,
            'payment_url' => null,
            'paid_at' => null,
            'notes' => null,
        ];
    }
}
