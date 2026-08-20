<?php

namespace Tests\Feature;

use App\Livewire\CatalogPage;
use App\Livewire\ProductDetail;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogAndProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_filters_products_by_search_category_price_stock_and_sort(): void
    {
        $category = Category::factory()->create(['name' => 'Kursi']);

        $chair = Product::factory()->create([
            'name' => 'Kursi Jati Ukir',
            'price' => 500000,
            'availability' => 'ready',
        ]);
        $chair->categories()->attach($category);

        $table = Product::factory()->create([
            'name' => 'Meja Makan Mahoni',
            'price' => 2000000,
            'availability' => 'pre_order',
        ]);

        $expensiveChair = Product::factory()->create([
            'name' => 'Kursi Mewah Sonokeling',
            'price' => 3000000,
            'availability' => 'ready',
        ]);
        $expensiveChair->categories()->attach($category);

        // Search by keyword.
        Livewire::test(CatalogPage::class)
            ->set('search', 'Kursi')
            ->assertSee('Kursi Jati Ukir')
            ->assertSee('Kursi Mewah Sonokeling')
            ->assertDontSee('Meja Makan Mahoni');

        // Filter by category.
        Livewire::test(CatalogPage::class)
            ->set('selectedCategories', [$category->id])
            ->assertSee('Kursi Jati Ukir')
            ->assertDontSee('Meja Makan Mahoni');

        // Filter by price range.
        Livewire::test(CatalogPage::class)
            ->set('priceMin', 1000000)
            ->set('priceMax', 2500000)
            ->assertSee('Meja Makan Mahoni')
            ->assertDontSee('Kursi Jati Ukir')
            ->assertDontSee('Kursi Mewah Sonokeling');

        // Filter by ready-stock availability.
        Livewire::test(CatalogPage::class)
            ->set('readyStock', true)
            ->assertSee('Kursi Jati Ukir')
            ->assertSee('Kursi Mewah Sonokeling')
            ->assertDontSee('Meja Makan Mahoni');

        // Sort by price ascending.
        $component = Livewire::test(CatalogPage::class)->set('sort', 'price_asc');
        $names = $component->viewData('products')->pluck('name')->values()->all();
        $this->assertSame(['Kursi Jati Ukir', 'Meja Makan Mahoni', 'Kursi Mewah Sonokeling'], $names);
    }

    public function test_catalog_reset_filters_restores_default_state(): void
    {
        Livewire::test(CatalogPage::class)
            ->set('search', 'Kursi')
            ->set('priceMin', 100000)
            ->set('priceMax', 500000)
            ->set('sort', 'price_desc')
            ->set('readyStock', true)
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('priceMin', null)
            ->assertSet('priceMax', null)
            ->assertSet('sort', 'latest')
            ->assertSet('readyStock', false)
            ->assertSet('selectedCategories', []);
    }

    public function test_product_detail_quantity_respects_minimum_and_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $component = Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->assertSet('quantity', 1)
            ->call('decrementQty')
            ->assertSet('quantity', 1); // cannot go below 1

        $component->call('incrementQty')->assertSet('quantity', 2)
            ->call('incrementQty')->assertSet('quantity', 3)
            ->call('incrementQty')->assertSet('quantity', 3); // cannot exceed stock
    }

    public function test_authenticated_user_can_add_product_to_cart_from_product_detail(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user);

        Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertDispatched('cart-updated')
            ->assertDispatched('alert');

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_out_of_stock_product_is_not_added_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->outOfStock()->create();

        $this->actingAs($user);

        Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->call('addToCart')
            ->assertDispatched('alert', type: 'error');

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_inactive_product_detail_returns_404(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->get('/products/' . $product->slug)->assertNotFound();
    }
}
