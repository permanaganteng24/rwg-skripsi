<?php

namespace Tests\Feature;

use App\Livewire\CatalogPage;
use App\Livewire\ProductDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogAndProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_filters_products_by_search_category_price_stock_and_sort(): void
    {
        $chairs = $this->category(['name' => 'Chairs', 'slug' => 'chairs']);
        $tables = $this->category(['name' => 'Tables', 'slug' => 'tables']);

        $cheapChair = $this->product([
            'name' => 'Alpha Teak Chair',
            'slug' => 'alpha-teak-chair',
            'price' => 1000000,
            'stock' => 5,
            'availability' => 'ready',
        ], $chairs);

        $expensiveTable = $this->product([
            'name' => 'Beta Coffee Table',
            'slug' => 'beta-coffee-table',
            'price' => 4000000,
            'stock' => 3,
            'availability' => 'pre_order',
        ], $tables);

        Livewire::test(CatalogPage::class)
            ->set('search', 'Chair')
            ->assertSee($cheapChair->name)
            ->assertDontSee($expensiveTable->name)
            ->set('search', '')
            ->set('selectedCategories', [$tables->id])
            ->assertSee($expensiveTable->name)
            ->assertDontSee($cheapChair->name)
            ->set('selectedCategories', [])
            ->set('priceMin', 3000000)
            ->assertSee($expensiveTable->name)
            ->assertDontSee($cheapChair->name)
            ->set('priceMin', null)
            ->set('priceMax', 3000000)
            ->assertSee($cheapChair->name)
            ->assertDontSee($expensiveTable->name)
            ->set('priceMax', null)
            ->set('readyStock', true)
            ->assertSee($cheapChair->name)
            ->assertDontSee($expensiveTable->name)
            ->set('readyStock', false)
            ->set('sort', 'price_asc')
            ->assertSeeInOrder([$cheapChair->name, $expensiveTable->name])
            ->set('sort', 'price_desc')
            ->assertSeeInOrder([$expensiveTable->name, $cheapChair->name]);
    }

    public function test_catalog_reset_filters_restores_default_state(): void
    {
        Livewire::test(CatalogPage::class)
            ->set('search', 'Chair')
            ->set('selectedCategories', [1])
            ->set('priceMin', 100000)
            ->set('priceMax', 200000)
            ->set('readyStock', true)
            ->set('sort', 'price_asc')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('selectedCategories', [])
            ->assertSet('priceMin', null)
            ->assertSet('priceMax', null)
            ->assertSet('readyStock', false)
            ->assertSet('sort', 'latest');
    }

    public function test_product_detail_quantity_respects_minimum_and_stock(): void
    {
        $product = $this->product([
            'slug' => 'limited-chair',
            'stock' => 2,
        ]);

        Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->call('decrementQty')
            ->assertSet('quantity', 1)
            ->call('incrementQty')
            ->assertSet('quantity', 2)
            ->call('incrementQty')
            ->assertSet('quantity', 2);
    }

    public function test_authenticated_user_can_add_product_to_cart_from_product_detail(): void
    {
        $user = $this->customerUser();
        $product = $this->product(['slug' => 'cart-chair', 'stock' => 5]);

        $this->actingAs($user);

        Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->set('quantity', 2)
            ->call('addToCart');

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_out_of_stock_product_is_not_added_to_cart(): void
    {
        $user = $this->customerUser();
        $product = $this->product(['slug' => 'empty-stock-chair', 'stock' => 0]);

        $this->actingAs($user);

        Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->call('addToCart')
            ->assertDispatched('alert');

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_inactive_product_detail_returns_404(): void
    {
        $product = $this->product([
            'slug' => 'inactive-chair',
            'is_active' => false,
        ]);

        $this->get('/products/' . $product->slug)->assertNotFound();
    }
}
