<?php

namespace Tests\Feature;

use App\Livewire\CartPage;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartAndCouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_cart_can_increment_decrement_and_remove_items(): void
    {
        $user = $this->customerUser();
        $product = $this->product(['price' => 2000000]);

        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user);

        Livewire::test(CartPage::class)
            ->assertSet('subtotal', 2000000)
            ->assertSet('grand_total', 2000000)
            ->call('incrementQty', $product->id)
            ->assertSet('subtotal', 4000000)
            ->call('decrementQty', $product->id)
            ->assertSet('subtotal', 2000000)
            ->call('decrementQty', $product->id)
            ->assertSet('subtotal', 2000000)
            ->call('removeItem', $product->id)
            ->assertSet('subtotal', 0);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_guest_cart_can_be_rendered_from_cookie(): void
    {
        $product = $this->product([
            'name' => 'Guest Cart Chair',
            'price' => 1200000,
        ]);

        $this->withCookie('cart_items', json_encode([
            $this->cartCookieItem($product, 2),
        ]))
            ->get('/cart')
            ->assertOk()
            ->assertSee('Guest Cart Chair')
            ->assertSee('1 Items')
            ->assertSee('2.400.000');
    }

    public function test_coupon_validation_rejects_invalid_inactive_expired_and_minimum_spend_cases(): void
    {
        $user = $this->customerUser();
        $product = $this->product(['price' => 1000000]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        Coupon::factory()->create(['code' => 'INACTIVE', 'is_active' => false]);
        Coupon::factory()->create(['code' => 'EXPIRED', 'expiry_date' => now()->subDay()->toDateString()]);
        Coupon::factory()->create(['code' => 'MINIMUM', 'min_spend' => 2000000]);

        $this->actingAs($user);

        Livewire::test(CartPage::class)
            ->set('coupon_code', 'UNKNOWN')
            ->call('applyCoupon')
            ->assertSet('applied_coupon_code', null)
            ->set('coupon_code', 'INACTIVE')
            ->call('applyCoupon')
            ->assertSet('applied_coupon_code', null)
            ->set('coupon_code', 'EXPIRED')
            ->call('applyCoupon')
            ->assertSet('applied_coupon_code', null)
            ->set('coupon_code', 'MINIMUM')
            ->call('applyCoupon')
            ->assertSet('applied_coupon_code', null);
    }

    public function test_fixed_coupon_applies_discount_and_can_be_removed(): void
    {
        $user = $this->customerUser();
        $product = $this->product(['price' => 2000000]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        Coupon::factory()->create([
            'code' => 'HEMAT100',
            'type' => 'fixed',
            'value' => 100000,
        ]);

        $this->actingAs($user);

        Livewire::test(CartPage::class)
            ->set('coupon_code', 'HEMAT100')
            ->call('applyCoupon')
            ->assertSet('applied_coupon_code', 'HEMAT100')
            ->assertSet('discount', 100000)
            ->assertSet('grand_total', 1900000)
            ->call('removeCoupon')
            ->assertSet('applied_coupon_code', null)
            ->assertSet('discount', 0)
            ->assertSet('grand_total', 2000000);
    }

    public function test_percent_coupon_and_large_fixed_coupon_calculate_safely(): void
    {
        $user = $this->customerUser();
        $product = $this->product(['price' => 1000000]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        Coupon::factory()->create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
        ]);

        Coupon::factory()->create([
            'code' => 'FREEALL',
            'type' => 'fixed',
            'value' => 999999999,
        ]);

        $this->actingAs($user);

        Livewire::test(CartPage::class)
            ->set('coupon_code', 'WELCOME10')
            ->call('applyCoupon')
            ->assertSet('discount', 200000)
            ->assertSet('grand_total', 1800000)
            ->set('coupon_code', 'FREEALL')
            ->call('applyCoupon')
            ->assertSet('discount', 2000000)
            ->assertSet('grand_total', 0);
    }
}
