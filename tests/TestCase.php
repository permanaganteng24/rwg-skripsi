<?php

namespace Tests;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function adminUser(array $attributes = []): User
    {
        return User::factory()->admin()->create($attributes);
    }

    protected function customerUser(array $attributes = []): User
    {
        return User::factory()->customer()->create($attributes);
    }

    protected function category(array $attributes = []): Category
    {
        return Category::factory()->create($attributes);
    }

    protected function product(array $attributes = [], ?Category $category = null): Product
    {
        $product = Product::factory()->create($attributes);

        ProductGallery::factory()->for($product)->create([
            'image_url' => 'products/test-product.jpg',
            'is_thumbnail' => true,
        ]);

        $product->categories()->attach(($category ?? $this->category())->id);

        return $product->refresh();
    }

    protected function orderFor(User $user, array $attributes = [], ?Product $product = null): Order
    {
        $product ??= $this->product();

        $order = Order::factory()->for($user)->create(array_merge([
            'total_product_price' => $product->price,
            'discount_amount' => 0,
            'grand_total' => $product->price,
        ], $attributes));

        OrderItem::factory()->for($order)->for($product)->create([
            'product_name' => $product->name,
            'price_per_unit' => $product->price,
            'subtotal' => $product->price,
        ]);

        return $order->refresh();
    }

    protected function cartCookieItem(Product $product, int $quantity = 1): array
    {
        return [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->galleries->first()->image_url ?? '',
            'price' => (float) $product->price,
            'quantity' => $quantity,
            'total_amount' => (float) $product->price * $quantity,
        ];
    }

    protected function seedIndonesiaLocations(): array
    {
        $prefix = config('laravolt.indonesia.table_prefix', 'indonesia_');
        $provincesTable = $prefix . 'provinces';
        $citiesTable = $prefix . 'cities';
        $districtsTable = $prefix . 'districts';

        if (! Schema::hasTable($provincesTable)) {
            Schema::create($provincesTable, function (Blueprint $table): void {
                $table->id();
                $table->char('code', 2)->unique();
                $table->string('name');
                $table->text('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable($citiesTable)) {
            Schema::create($citiesTable, function (Blueprint $table): void {
                $table->id();
                $table->char('code', 4)->unique();
                $table->char('province_code', 2);
                $table->string('name');
                $table->text('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable($districtsTable)) {
            Schema::create($districtsTable, function (Blueprint $table): void {
                $table->id();
                $table->char('code', 7)->unique();
                $table->char('city_code', 4);
                $table->string('name');
                $table->text('meta')->nullable();
                $table->timestamps();
            });
        }

        DB::table($provincesTable)->insertOrIgnore([
            [
                'code' => '52',
                'name' => 'Nusa Tenggara Barat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table($citiesTable)->insertOrIgnore([
            [
                'code' => '5271',
                'province_code' => '52',
                'name' => 'KOTA MATARAM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5202',
                'province_code' => '52',
                'name' => 'KABUPATEN SUMBAWA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table($districtsTable)->insertOrIgnore([
            [
                'code' => '5271010',
                'city_code' => '5271',
                'name' => 'MATARAM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5202010',
                'city_code' => '5202',
                'name' => 'SUMBAWA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return [
            'province' => '52',
            'mataram_city' => '5271',
            'mataram_district' => '5271010',
            'cargo_city' => '5202',
            'cargo_district' => '5202010',
        ];
    }
}
