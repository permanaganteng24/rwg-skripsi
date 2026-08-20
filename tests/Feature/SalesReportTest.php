<?php

namespace Tests\Feature;

use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalesReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_the_admin_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_open_the_dashboard_with_sales_widgets(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_stats_overview_shows_total_revenue_from_paid_orders_only(): void
    {
        $admin = User::factory()->admin()->create();

        Order::factory()->create([
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'grand_total' => 1000000,
            'discount_amount' => 0,
        ]);

        Order::factory()->create([
            'payment_status' => 'unpaid',
            'order_status' => 'new',
            'grand_total' => 500000,
            'discount_amount' => 0,
        ]);

        $this->actingAs($admin);

        Livewire::test(StatsOverview::class)
            ->assertOk()
            ->assertSee('Total Pemasukan (Paid)')
            ->assertSee('Rp 1.000.000')
            ->assertSee('Pesanan Baru')
            ->assertSee('Total Diskon Diberikan');
    }

    public function test_stats_overview_counts_new_orders_needing_processing(): void
    {
        $admin = User::factory()->admin()->create();

        Order::factory()->count(3)->create(['order_status' => 'new']);
        Order::factory()->create(['order_status' => 'completed']);

        $this->actingAs($admin);

        Livewire::test(StatsOverview::class)
            ->assertOk()
            ->assertSee('3');
    }

    public function test_stats_overview_sums_discount_given_across_all_orders(): void
    {
        $admin = User::factory()->admin()->create();

        Order::factory()->create(['discount_amount' => 50000]);
        Order::factory()->create(['discount_amount' => 25000]);

        $this->actingAs($admin);

        Livewire::test(StatsOverview::class)
            ->assertOk()
            ->assertSee('Rp 75.000');
    }

    public function test_sales_chart_widget_renders_for_admin(): void
    {
        $admin = User::factory()->admin()->create();
        Order::factory()->create(['payment_status' => 'paid', 'order_status' => 'processing', 'grand_total' => 500000]);

        $this->actingAs($admin);

        Livewire::test(SalesChart::class)->assertOk();
    }
}

