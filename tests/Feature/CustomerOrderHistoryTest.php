<?php

namespace Tests\Feature;

use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_a_customers_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($admin)
            ->get("/admin/users/{$customer->id}/edit")
            ->assertOk();
    }

    public function test_admin_sees_only_that_customers_own_transaction_history(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['name' => 'Siti Customer', 'role' => 'customer']);
        $otherCustomer = User::factory()->create(['name' => 'Rudi Hartono', 'role' => 'customer']);

        Order::factory()->create([
            'user_id' => $customer->id,
            'code' => 'ORD-SITI0001',
            'grand_total' => 750000,
            'payment_status' => 'paid',
            'order_status' => 'shipped',
        ]);

        Order::factory()->create([
            'user_id' => $otherCustomer->id,
            'code' => 'ORD-RUDI0001',
        ]);

        $this->actingAs($admin);

        Livewire::test(OrdersRelationManager::class, [
            'ownerRecord' => $customer,
            'pageClass' => EditUser::class,
        ])
            ->assertOk()
            ->assertSee('ORD-SITI0001')
            ->assertDontSee('ORD-RUDI0001');
    }

    public function test_transaction_history_shows_empty_when_customer_has_no_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($admin);

        Livewire::test(OrdersRelationManager::class, [
            'ownerRecord' => $customer,
            'pageClass' => EditUser::class,
        ])->assertOk();

        $this->assertDatabaseCount('orders', 0);
    }
}
