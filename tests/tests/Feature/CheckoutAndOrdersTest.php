<?php

namespace Tests\Feature;

use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\SeedsIndonesianRegions;
use Tests\TestCase;

class CheckoutAndOrdersTest extends TestCase
{
    use RefreshDatabase;
    use SeedsIndonesianRegions;

    public function test_checkout_redirects_to_catalog_when_cart_is_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/checkout')
            ->assertRedirect(route('products.index'));
    }

    public function test_checkout_validates_required_customer_fields(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $this->actingAs($user);

        Livewire::test(CheckoutPage::class)
            ->set('first_name', '')
            ->set('phone', '')
            ->set('address', '')
            ->set('email', 'not-an-email')
            ->call('placeOrder')
            ->assertHasErrors(['first_name', 'phone', 'address', 'email']);

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_local_lombok_checkout_creates_waiting_payment_order_and_clears_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 250000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $region = $this->seedLombokRegion();

        $this->actingAs($user);

        Livewire::test(CheckoutPage::class)
            ->set('first_name', 'Rizqi')
            ->set('last_name', 'Pratama')
            ->set('email', 'rizqi@example.com')
            ->set('phone', '081234567890')
            ->set('address', 'Jl. Melati No. 10')
            ->set('selectedProvince', $region['province'])
            ->call('updatedSelectedProvince', $region['province'])
            ->set('selectedCity', $region['city'])
            ->call('updatedSelectedCity', $region['city'])
            ->assertSet('is_lombok', true)
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'order_status' => 'waiting_payment',
            'shipping_method' => 'Free Local Shipping',
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_non_lombok_indonesia_checkout_creates_waiting_quote_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 250000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $region = $this->seedNonLombokRegion();

        $this->actingAs($user);

        Livewire::test(CheckoutPage::class)
            ->set('first_name', 'Andi')
            ->set('email', 'andi@example.com')
            ->set('phone', '081234567890')
            ->set('address', 'Jl. Sudirman No. 1')
            ->set('selectedProvince', $region['province'])
            ->call('updatedSelectedProvince', $region['province'])
            ->set('selectedCity', $region['city'])
            ->call('updatedSelectedCity', $region['city'])
            ->assertSet('is_lombok', false)
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'order_status' => 'waiting_quote',
        ]);
    }

    public function test_international_checkout_requires_manual_country_and_city_then_applies(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 250000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $this->actingAs($user);

        // Missing manual country/city is rejected.
        Livewire::test(CheckoutPage::class)
            ->set('location_type', 'international')
            ->call('updatedLocationType', 'international')
            ->set('first_name', 'John')
            ->set('email', 'john@example.com')
            ->set('phone', '0811111111')
            ->set('address', '123 Main Street')
            ->call('placeOrder')
            ->assertHasErrors(['manual_country_name', 'manual_city']);

        $this->assertDatabaseCount('orders', 0);

        // Filling in manual country/city allows the order to be placed.
        Livewire::test(CheckoutPage::class)
            ->set('location_type', 'international')
            ->call('updatedLocationType', 'international')
            ->set('first_name', 'John')
            ->set('email', 'john@example.com')
            ->set('phone', '0811111111')
            ->set('address', '123 Main Street')
            ->set('manual_country_name', 'Australia')
            ->set('manual_state', 'New South Wales')
            ->set('manual_city', 'Sydney')
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'shipping_country' => 'Australia',
            'shipping_city' => 'Sydney',
            'order_status' => 'waiting_quote',
        ]);
    }

    public function test_user_can_only_view_their_own_orders(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->get('/my-orders/' . $order->id)
            ->assertOk();

        $this->actingAs($otherUser)
            ->get('/my-orders/' . $order->id)
            ->assertNotFound();
    }

    public function test_success_page_shows_order_summary(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'code' => 'ORD-SUMMARY1',
            'grand_total' => 500000,
        ]);

        $response = $this->actingAs($user)->get('/success/' . $order->id);

        $response->assertOk();
        $response->assertSee('ORD-SUMMARY1');
    }

    // --- US-007: Melihat status pesanan (daftar "Pesanan Saya") ---

    public function test_my_orders_page_lists_authenticated_users_orders_with_status(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $waitingPaymentOrder = Order::factory()->create([
            'user_id' => $user->id,
            'code' => 'ORD-WAITING1',
            'order_status' => 'waiting_payment',
        ]);
        OrderItem::factory()->create(['order_id' => $waitingPaymentOrder->id, 'product_id' => $product->id]);

        $processingOrder = Order::factory()->create([
            'user_id' => $user->id,
            'code' => 'ORD-PROCESS1',
            'order_status' => 'processing',
        ]);
        OrderItem::factory()->create(['order_id' => $processingOrder->id, 'product_id' => $product->id]);

        $this->actingAs($user);

        Livewire::test(MyOrdersPage::class)
            ->assertSee('ORD-WAITING1')
            ->assertSee('ORD-PROCESS1')
            ->assertSee('Waiting payment') // str_replace('_',' ', ucfirst('waiting_payment'))
            ->assertSee('Processing');
    }

    public function test_my_orders_page_only_lists_the_authenticated_users_own_orders(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();

        $ownOrder = Order::factory()->create(['user_id' => $owner->id, 'code' => 'ORD-MINE001']);
        OrderItem::factory()->create(['order_id' => $ownOrder->id, 'product_id' => $product->id]);

        $othersOrder = Order::factory()->create(['user_id' => $otherUser->id, 'code' => 'ORD-OTHER01']);
        OrderItem::factory()->create(['order_id' => $othersOrder->id, 'product_id' => $product->id]);

        $this->actingAs($owner);

        Livewire::test(MyOrdersPage::class)
            ->assertSee('ORD-MINE001')
            ->assertDontSee('ORD-OTHER01');
    }

    public function test_my_orders_page_shows_empty_state_when_user_has_no_orders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(MyOrdersPage::class)
            ->assertSee('No Orders Yet');
    }
}
