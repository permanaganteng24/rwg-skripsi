<?php

namespace Tests\Feature;

use App\Livewire\CartPage;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartAndCouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_can_be_rendered_from_cookie(): void
    {
        $product = Product::factory()->create(['name' => 'Lemari Jati Klasik', 'price' => 1500000]);

        $cartItems = [[
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => '',
            'price' => $product->price,
            'quantity' => 2,
            'total_amount' => $product->price * 2,
        ]];

        $response = $this->withUnencryptedCookie('cart_items', json_encode($cartItems))->get('/cart');

        $response->assertOk();
        $response->assertSee('Lemari Jati Klasik');
    }

    public function test_coupon_validation_rejects_invalid_inactive_expired_and_minimum_spend(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $inactive = Coupon::factory()->inactive()->create(['code' => 'INACTIVE1']);
        $expired = Coupon::factory()->expired()->create(['code' => 'EXPIRED1']);
        $highMinSpend = Coupon::factory()->minSpend(5000000)->create(['code' => 'BIGMIN']);

        $this->actingAs($user);

        // Unknown code.
        Livewire::test(CartPage::class)
            ->set('coupon_code', 'DOES-NOT-EXIST')
            ->call('applyCoupon')
            ->assertSessionHas('error');

        // Inactive coupon.
        Livewire::test(CartPage::class)
            ->set('coupon_code', $inactive->code)
            ->call('applyCoupon')
            ->assertSessionHas('error');

        // Expired coupon.
        Livewire::test(CartPage::class)
            ->set('coupon_code', $expired->code)
            ->call('applyCoupon')
            ->assertSessionHas('error');

        // Minimum spend not met.
        Livewire::test(CartPage::class)
            ->set('coupon_code', $highMinSpend->code)
            ->call('applyCoupon')
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('cart_items', ['id' => 0]); // sanity: no crash occurred
    }

    public function test_fixed_coupon_applies_discount_and_can_be_removed(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 200000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $coupon = Coupon::factory()->create(['code' => 'DISKON50K', 'type' => 'fixed', 'value' => 50000, 'min_spend' => 0]);

        $this->actingAs($user);

        $component = Livewire::test(CartPage::class)
            ->set('coupon_code', $coupon->code)
            ->call('applyCoupon')
            ->assertSet('applied_coupon_code', 'DISKON50K')
            ->assertSet('discount', 50000)
            ->assertSet('grand_total', 150000);

        $component->call('removeCoupon')
            ->assertSet('applied_coupon_code', null)
            ->assertSet('discount', 0)
            ->assertSet('grand_total', 200000);
    }

    public function test_percent_coupon_and_large_fixed_coupon_calculate_safely(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100000]);
        CartItem::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 1]);

        // 10% of a 100,000 subtotal.
        $percentCoupon = Coupon::factory()->percent(10)->create(['code' => 'PERCENT10', 'min_spend' => 0]);

        $this->actingAs($user);

        Livewire::test(CartPage::class)
            ->set('coupon_code', $percentCoupon->code)
            ->call('applyCoupon')
            ->assertSet('discount', 10000)
            ->assertSet('grand_total', 90000);

        // A fixed coupon larger than the subtotal must never push the total negative.
        $hugeFixedCoupon = Coupon::factory()->create(['code' => 'HUGEFIXED', 'type' => 'fixed', 'value' => 999999999, 'min_spend' => 0]);

        Livewire::test(CartPage::class)
            ->set('coupon_code', $hugeFixedCoupon->code)
            ->call('applyCoupon')
            ->assertSet('discount', 100000)
            ->assertSet('grand_total', 0);
    }
}
