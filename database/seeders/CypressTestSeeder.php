<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;

class CypressTestSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::create([
            'name' => 'Cypress Customer',
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

        $chair = Product::create([
            'name' => 'Cypress Test Chair',
            'slug' => 'cypress-test-chair',
            'description' => '<p>Produk khusus untuk automated testing Cypress.</p>',
            'price' => 500000,
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

        Product::create([
            'name' => 'Produk Untuk Dihapus',
            'slug' => 'produk-untuk-dihapus',
            'description' => '<p>Produk khusus untuk testing hapus produk.</p>',
            'price' => 100000,
            'weight_kg' => 1,
            'length_cm' => 10,
            'width_cm' => 10,
            'height_cm' => 10,
            'material' => 'Test Material',
            'finishing' => 'Test Finishing',
            'stock' => 5,
            'availability' => 'ready',
            'is_active' => true,
            'is_featured' => false,
        ]);

        Category::create([
            'name' => 'Kategori Untuk Dihapus',
            'slug' => 'kategori-untuk-dihapus',
            'icon' => 'test-category.jpg',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'code' => 'ORD-CYPRESS-TEST',
            'shipping_name' => 'Cypress Customer',
            'shipping_phone' => '081234567890',
            'shipping_email' => 'customer@cypress.test',
            'shipping_address' => 'Jl. Cypress Testing No. 1',
            'shipping_country' => 'Indonesia',
            'shipping_province' => 'Nusa Tenggara Barat',
            'shipping_city' => 'Kota Mataram',
            'shipping_district' => 'Mataram',
            'shipping_postal_code' => '83115',
            'shipping_method' => 'Free Local Shipping',
            'total_weight_kg' => 5,
            'total_product_price' => 500000,
            'grand_total' => 500000,
            'order_status' => 'pending',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $chair->id,
            'product_name' => $chair->name,
            'quantity' => 1,
            'price_per_unit' => $chair->price,
            'subtotal' => $chair->price,
        ]);

        $reviewOrder = Order::create([
            'user_id' => $customer->id,
            'code' => 'ORD-REVIEW-TEST',
            'shipping_name' => 'Cypress Customer',
            'shipping_phone' => '081234567890',
            'shipping_email' => 'customer@cypress.test',
            'shipping_address' => 'Jl. Cypress Testing No. 1',
            'shipping_country' => 'Indonesia',
            'shipping_province' => 'Nusa Tenggara Barat',
            'shipping_city' => 'Kota Mataram',
            'shipping_district' => 'Mataram',
            'shipping_postal_code' => '83115',
            'shipping_method' => 'Free Local Shipping',
            'total_weight_kg' => 5,
            'total_product_price' => 500000,
            'grand_total' => 500000,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $reviewOrder->id,
            'product_id' => $chair->id,
            'product_name' => $chair->name,
            'quantity' => 1,
            'price_per_unit' => $chair->price,
            'subtotal' => $chair->price,
        ]);
    }
}