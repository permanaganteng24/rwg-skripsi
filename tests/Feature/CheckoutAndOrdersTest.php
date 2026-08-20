<?php

namespace Tests\Feature;

use App\Livewire\CheckoutPage;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutAndOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_to_catalog_when_cart_is_empty(): void
    {
        $this->actingAs($this->customerUser());

        Livewire::test(CheckoutPage::class)
            ->assertRedirect(route('products.index'));
    }

    public function test_checkout_validates_required_customer_fields(): void
    {
        $user = $this->customerUser();
        $product = $this->product();
        $this->seedIndonesiaLocations();
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $this->actingAs($user);

        Livewire::test(CheckoutPage::class)
            ->set('first_name', '')
            ->set('email', '')
            ->call('placeOrder')
            ->assertHasErrors([
                'first_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
            ]);
    }

    public function test_local_lombok_checkout_creates_waiting_payment_order_and_clears_cart(): void
    {
        $locations = $this->seedIndonesiaLocations();
        $user = $this->customerUser(['name' => 'Ijat Lombok', 'email' => 'ijat@example.com']);
        $product = $this->product(['price' => 2500000]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user);

        Livewire::test(CheckoutPage::class)
            ->set('phone', '08123456789')
            ->set('address', 'Jl. Majapahit No 10')
            ->set('selectedProvince', $locations['province'])
            ->set('selectedCity', $locations['mataram_city'])
            ->set('selectedDistrict', $locations['mataram_district'])
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'shipping_city' => 'KOTA MATARAM',
            'shipping_method' => 'Free Local Shipping',
            'order_status' => 'waiting_payment',
            'payment_status' => 'unpaid',
            'grand_total' => 2500000,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_non_lombok_indonesia_checkout_creates_waiting_quote_order(): void
    {
        $locations = $this->seedIndonesiaLocations();
        $user = $this->customerUser(['email' => 'cargo@example.com']);
        $product = $this->product(['price' => 3000000]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user);

        Livewire::test(CheckoutPage::class)
            ->set('phone', '08123456789')
            ->set('address', 'Jl. Pelabuhan No 1')
            ->set('selectedProvince', $locations['province'])
            ->set('selectedCity', $locations['cargo_city'])
            ->set('selectedDistrict', $locations['cargo_district'])
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'shipping_city' => 'KABUPATEN SUMBAWA',
            'shipping_method' => 'Cargo (Pending Confirmation)',
            'order_status' => 'waiting_quote',
        ]);
    }

    public function test_international_checkout_requires_manual_country_and_city_then_applies_coupon(): void
    {
        $user = $this->customerUser(['email' => 'john@example.com']);
        $product = $this->product(['price' => 6000000]);
        $this->seedIndonesiaLocations();
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        Coupon::factory()->create(['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10]);

        $this->actingAs($user);
        Session::put('coupon', ['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'min_spend' => 0]);

        Livewire::test(CheckoutPage::class)
            ->set('location_type', 'international')
            ->set('phone', '+15550199')
            ->set('address', '123 Beverly Hills')
            ->call('placeOrder')
            ->assertHasErrors([
                'manual_country_name' => 'required',
                'manual_city' => 'required',
            ])
            ->set('manual_country_name', 'United States')
            ->set('manual_state', 'California')
            ->set('manual_city', 'Los Angeles')
            ->call('placeOrder')
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'shipping_country' => 'United States',
            'shipping_city' => 'Los Angeles',
            'discount_amount' => 600000,
            'grand_total' => 5400000,
            'order_status' => 'waiting_quote',
        ]);
    }

    public function test_user_can_only_view_their_own_orders(): void
    {
        $owner = $this->customerUser();
        $other = $this->customerUser();
        $ownerOrder = $this->orderFor($owner, ['code' => 'ORD-OWNER-001']);
        $otherOrder = $this->orderFor($other, ['code' => 'ORD-OTHER-001']);

        $this->actingAs($owner);

        $this->get('/my-orders')
            ->assertOk()
            ->assertSee($ownerOrder->code)
            ->assertDontSee($otherOrder->code);

     $response = $this->get('/my-orders/' . $ownerOrder->id);

if ($response->exception) {
    dd(
        get_class($response->exception),
        $response->exception->getMessage()
    );
}

$response->assertOk();

       $this->get('/my-orders/' . $otherOrder->id)
    ->assertNotFound();
    }

    public function test_success_page_shows_order_summary(): void
    {
        $user = $this->customerUser();
        $order = $this->orderFor($user, ['code' => 'ORD-SUCCESS-001']);

        $this->actingAs($user)
            ->get('/success/' . $order->id)
            ->assertOk()
            ->assertSee('ORD-SUCCESS-001')
            ->assertSee('Total Bill');
    }
}
