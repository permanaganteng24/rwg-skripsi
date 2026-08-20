<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_owner_and_admin_can_download_invoice(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($owner)
            ->get('/invoice/' . $order->id)
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($admin)
            ->get('/invoice/' . $order->id)
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_other_customer_cannot_download_invoice(): void
    {
        $owner = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($otherCustomer)
            ->get('/invoice/' . $order->id)
            ->assertForbidden();
    }
}
