<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => 'Home',
            'recipient_name' => fake()->name(),
            'phone' => '08123456789',
            'country' => 'Indonesia',
            'province' => 'Nusa Tenggara Barat',
            'city' => 'KOTA MATARAM',
            'district' => 'MATARAM',
            'postal_code' => '83115',
            'address_line' => fake()->streetAddress(),
            'is_default' => false,
        ];
    }
}
