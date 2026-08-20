<?php

namespace Tests\Feature;

use App\Livewire\MyOrderDetailPage;
use App\Livewire\SuccessPage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class MidtransPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_pay_now_generates_a_snap_token_for_an_unpaid_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'grand_total' => 750000,
            'payment_status' => 'unpaid',
        ]);

        $snapMock = Mockery::mock('alias:Midtrans\Snap');
        $snapMock->shouldReceive('getSnapToken')
            ->once()
            ->andReturn('fake-snap-token-123');

        $this->actingAs($user);

        Livewire::test(SuccessPage::class, ['order_id' => $order->id])
            ->call('payNow')
            ->assertSet('snapToken', 'fake-snap-token-123')
            ->assertDispatched('show-snap-popup', token: 'fake-snap-token-123');
    }

    public function test_pay_now_dispatches_error_when_midtrans_fails(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $snapMock = Mockery::mock('alias:Midtrans\Snap');
        $snapMock->shouldReceive('getSnapToken')
            ->once()
            ->andThrow(new \Exception('Midtrans service unavailable'));

        $this->actingAs($user);

        Livewire::test(SuccessPage::class, ['order_id' => $order->id])
            ->call('payNow')
            ->assertDispatched('midtrans-error');
    }

    public function test_payment_success_event_marks_order_as_paid_and_processing(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_status' => 'waiting_payment',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($user);

        Livewire::test(SuccessPage::class, ['order_id' => $order->id])
            ->dispatch('payment-success', ['status' => 'settlement'])
            ->assertRedirect('/my-orders');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'processing',
            'payment_status' => 'paid',
        ]);
    }

    public function test_payment_success_event_on_order_detail_page_also_marks_order_as_paid(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_status' => 'waiting_payment',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($user);

        Livewire::test(MyOrderDetailPage::class, ['order_id' => $order->id])
            ->dispatch('payment-success', ['status' => 'settlement']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'processing',
            'payment_status' => 'paid',
        ]);
    }
}
