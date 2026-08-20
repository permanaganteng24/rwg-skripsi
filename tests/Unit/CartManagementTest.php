<?php

namespace Tests\Unit;

use App\Helpers\CartManagement;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_cart_items_are_loaded_from_database_with_product_totals(): void
    {
        $user = $this->customerUser();
        $product = $this->product([
            'name' => 'Database Cart Chair',
            'price' => 1500000,
        ]);

        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->actingAs($user);

        $items = CartManagement::getCartItemsFromCookie();

        $this->assertCount(1, $items);
        $this->assertSame($product->id, $items[0]['product_id']);
        $this->assertSame('Database Cart Chair', $items[0]['name']);
        $this->assertEquals(4500000, $items[0]['total_amount']);
    }

    public function test_authenticated_add_item_merges_existing_cart_quantity_and_never_goes_below_one(): void
    {
        $user = $this->customerUser();
        $product = $this->product();

        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user);

        CartManagement::addItemToCartWithQty($product->id, 2);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        CartManagement::addItemToCartWithQty($product->id, -10);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    public function test_authenticated_remove_and_clear_cart_items_delete_database_rows(): void
    {
        $user = $this->customerUser();
        $firstProduct = $this->product();
        $secondProduct = $this->product();

        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $firstProduct->id,
        ]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $secondProduct->id,
        ]);

        $this->actingAs($user);

        CartManagement::removeCartItem($firstProduct->id);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $firstProduct->id,
        ]);

        CartManagement::clearCartItems();

        $this->assertDatabaseCount('cart_items', 0);
    }
}
