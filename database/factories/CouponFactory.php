<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'type' => 'fixed',
            'value' => 100000,
            'min_spend' => 0,
            'is_active' => true,
            'expiry_date' => now()->addMonth()->toDateString(),
        ];
    }
}
