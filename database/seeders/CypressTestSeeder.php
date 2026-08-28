<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class CypressTestSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Cypress Tester',
            'email' => 'customer@cypress.test',
            'role' => 'customer',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Cypress Admin',
            'email' => 'admin@cypress.test',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Product::create([
            'name' => 'Cypress Test Chair',
            'slug' => 'cypress-test-chair',
            'description' => '<p>Produk khusus untuk automated testing Cypress.</p>',
            'price' => 1000000,
            'weight_kg' => 5,
            'length_cm' => 50,
            'width_cm' => 50,
            'height_cm' => 80,
            'material' => 'Test Material',
            'finishing' => 'Test Finishing',
            'stock' => 10,
            'availability' => 'ready',
            'is_active' => true,
            'is_featured' => true,
        ]);
    }
}