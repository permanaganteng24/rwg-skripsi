<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
    }
}