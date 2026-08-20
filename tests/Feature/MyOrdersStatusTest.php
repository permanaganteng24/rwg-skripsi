<?php

namespace Tests\Feature;

use App\Livewire\MyOrdersPage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MyOrdersStatusTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertSee('Waiting payment')
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
