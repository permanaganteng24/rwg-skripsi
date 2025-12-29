<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
        \Laravolt\Indonesia\Seeds\DatabaseSeeder::class,
    ]);

        // SEED USERS -----
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@rizqiwood.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $customer1 = User::create([
            'name' => 'Ijat Pulu Pulu',
            'email' => 'ijat@gmail.com',
            'role' => 'customer',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $customer2 = User::create([
            'name' => 'John Doe (US)',
            'email' => 'john.doe@example.com',
            'role' => 'customer',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ----- SEED CATEGORIES -----
        $catLiving = Category::create([
            'name' => 'Living Room', 
            'slug' => 'living-room', 
            'icon' => 'living-room.jpg'
        ]);
        
        $catBed = Category::create([
            'name' => 'Bedroom', 
            'slug' => 'bedroom', 
            'icon' => 'bedroom.jpg'
        ]);
        
        $catKitchen = Category::create([
            'name' => 'Kitchen & Dining', 
            'slug' => 'kitchen-dining', 
            'icon' => 'kitchen.jpg'
        ]);
        
        $catDecor = Category::create([
            'name' => 'Decoration', 
            'slug' => 'decoration', 
            'icon' => 'teakroot.jpg'
        ]);

        // ----- SEED PRODUCTS -----
        // Produk 1: Kursi (Ready)
        $p1 = Product::create([
            'name' => 'Teak Lounge Chair - Minimalist Series',
            'slug' => 'teak-lounge-chair',
            'description' => '<p>Kursi santai dengan desain minimalis modern berbahan kayu jati solid asli Perhutani. Cocok untuk ruang tamu atau teras.</p>',
            'price' => 2500000,
            'weight_kg' => 12,
            'length_cm' => 60, 'width_cm' => 65, 'height_cm' => 80,
            'material' => 'Solid Teak Wood',
            'finishing' => 'Matte Natural',
            'stock' => 15,
            'availability' => 'ready',
            'is_active' => true,
            'is_featured' => true,
        ]);
        $p1->categories()->attach([$catLiving->id, $catDecor->id]);
        ProductGallery::create(['product_id' => $p1->id, 'image_url' => 'products/chair-1.jpg', 'is_thumbnail' => true]);
        ProductGallery::create(['product_id' => $p1->id, 'image_url' => 'products/chair-2.jpg', 'is_thumbnail' => false]);

        // Produk 2: Meja (Ready)
        $p2 = Product::create([
            'name' => 'Rustic Coffee Table',
            'slug' => 'rustic-coffee-table',
            'description' => '<p>Meja kopi gaya rustic dengan serat kayu alami yang menonjol. Tahan lama dan estetik.</p>',
            'price' => 1500000,
            'weight_kg' => 25,
            'length_cm' => 100, 'width_cm' => 60, 'height_cm' => 45,
            'material' => 'Teak Root',
            'finishing' => 'Rustic Oil',
            'stock' => 5,
            'availability' => 'ready',
            'is_active' => true,
            'is_featured' => true,
        ]);
        $p2->categories()->attach($catLiving->id);
        ProductGallery::create(['product_id' => $p2->id, 'image_url' => 'products/table-1.jpg', 'is_thumbnail' => true]);

        // Produk 3: Sofa (Pre-Order)
        $p3 = Product::create([
            'name' => 'Asgard Sofa 3 Seater',
            'slug' => 'asgard-sofa-3-seater',
            'description' => '<p>Sofa nyaman 3 dudukan dengan rangka kayu jati kokoh dan busa royal foam empuk.</p>',
            'price' => 6000000,
            'weight_kg' => 45,
            'length_cm' => 200, 'width_cm' => 80, 'height_cm' => 85,
            'material' => 'Teak Wood & Fabric',
            'finishing' => 'Walnut Brown',
            'stock' => 2,
            'availability' => 'pre_order',
            'is_active' => true,
            'is_featured' => true,
        ]);
        $p3->categories()->attach($catLiving->id);
        ProductGallery::create(['product_id' => $p3->id, 'image_url' => 'products/sofa-1.jpg', 'is_thumbnail' => true]);

        // ----- SEED COUPONS -----
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
            'is_active' => true,
        ]);
        Coupon::create([
            'code' => 'HEMAT100',
            'type' => 'fixed',
            'value' => 100000,
            'is_active' => true,
        ]);

        // ----- SEED REVIEWS -----
        Review::create([
            'user_id' => $customer1->id,
            'product_id' => $p1->id,
            'rating' => 5,
            'comment' => 'Barang sangat bagus, kayunya kokoh dan finishing halus. Pengiriman ke Mataram gratis!',
            'is_approved' => true,
        ]);
        Review::create([
            'user_id' => $customer2->id,
            'product_id' => $p1->id,
            'rating' => 4,
            'comment' => 'Good quality product, but shipping to US took quite some time. Packaging was excellent though.',
            'is_approved' => true,
        ]);

        // ----- SEED ORDERS -----
        
        // Order 1: Lokal (Sudah Bayar & Selesai)
        $order1 = Order::create([
            'user_id' => $customer1->id,
            'code' => 'ORD-LOC-001',
            'shipping_name' => 'Ijat Pulu Pulu',
            'shipping_phone' => '08123456789',
            'shipping_email' => 'permana@gmail.com',
            'shipping_address' => 'Jl. Majapahit No 10',
            'shipping_country' => 'Indonesia',
            'shipping_province' => 'Nusa Tenggara Barat',
            'shipping_city' => 'Kota Mataram',
            'shipping_district' => 'Mataram',
            'shipping_postal_code' => '83115',
            'shipping_method' => 'Free Local Shipping',
            'total_weight_kg' => 12,
            'total_product_price' => 2500000,
            'grand_total' => 2500000,
            'order_status' => 'completed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'quantity' => 1,
            'price_per_unit' => $p1->price,
            'subtotal' => $p1->price,
        ]);

        // Order 2: International (Menunggu Ongkir)
        $order2 = Order::create([
            'user_id' => $customer2->id,
            'code' => 'ORD-INT-002',
            'shipping_name' => 'John Doe',
            'shipping_phone' => '+1 555 0199',
            'shipping_email' => 'john@example.com',
            'shipping_address' => '123 Beverly Hills',
            'shipping_country' => 'United States',
            'shipping_province' => 'California',
            'shipping_city' => 'Los Angeles',
            'shipping_district' => '-',
            'shipping_postal_code' => '90210',
            'shipping_method' => 'Cargo (Pending Confirmation)',
            'total_weight_kg' => 45,
            'total_product_price' => 6000000,
            'shipping_price' => 0, 
            'grand_total' => 6000000, 
            'order_status' => 'waiting_quote', 
            'payment_status' => 'unpaid',
        ]);
        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p3->id,
            'product_name' => $p3->name,
            'quantity' => 1,
            'price_per_unit' => $p3->price,
            'subtotal' => $p3->price,
        ]);
    }
}