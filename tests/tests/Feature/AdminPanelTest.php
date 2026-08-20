<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\StatsOverview;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_panel(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    // --- US-009: Mengelola data produk ---

    public function test_admin_can_view_and_create_a_product(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(ListProducts::class)->assertOk();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name' => 'Kursi Tamu Jati',
                'slug' => 'kursi-tamu-jati',
                'price' => 1500000,
                'weight_kg' => 12,
                'stock' => 5,
                'availability' => 'ready',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'Kursi Tamu Jati',
            'slug' => 'kursi-tamu-jati',
        ]);
    }

    public function test_admin_can_edit_an_existing_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['stock' => 3, 'price' => 100000]);

        $this->actingAs($admin);

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm([
                'stock' => 20,
                'price' => 250000,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 20,
            'price' => 250000,
        ]);
    }

    public function test_product_form_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name' => '',
                'price' => null,
                'weight_kg' => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['name', 'price', 'weight_kg']);
    }

    // --- US-010: Mengelola kategori produk ---

    public function test_admin_can_view_and_create_a_category(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(ListCategories::class)->assertOk();

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Meja Kerja',
                'slug' => 'meja-kerja',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Meja Kerja',
            'slug' => 'meja-kerja',
        ]);
    }

    public function test_category_form_requires_name_and_unique_slug(): void
    {
        $admin = User::factory()->admin()->create();
        Category::factory()->create(['slug' => 'sofa']);

        $this->actingAs($admin);

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => '', 'slug' => 'sofa'])
            ->call('create')
            ->assertHasFormErrors(['name', 'slug' => 'unique']);
    }

    // --- US-011: Mengelola data pesanan pelanggan ---

    public function test_admin_can_view_customer_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();
        Order::factory()->create(['user_id' => $customer->id, 'code' => 'ORD-VISIBLE1']);

        $this->actingAs($admin);

        Livewire::test(ListOrders::class)
            ->assertOk()
            ->assertSee('ORD-VISIBLE1');
    }

    // --- US-012: Mengubah status pesanan ---

    public function test_admin_can_change_order_status(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create([
            'order_status' => 'new',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($admin);

        Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
            ->fillForm([
                'order_status' => 'shipped',
                'payment_status' => 'paid',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'shipped',
            'payment_status' => 'paid',
        ]);
    }

    public function test_admin_can_input_shipping_cost_which_moves_quote_to_waiting_payment(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create([
            'order_status' => 'waiting_quote',
            'total_product_price' => 500000,
            'discount_amount' => 0,
            'grand_total' => 500000,
        ]);

        $this->actingAs($admin);

        Livewire::test(ListOrders::class)
            ->callTableAction('input_ongkir', $order, data: [
                'shipping_price' => 75000,
                'notes' => 'Ongkir sudah dihitung.',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'waiting_payment',
            'shipping_price' => 75000,
            'grand_total' => 575000,
        ]);
    }

    // --- US-013: Melihat data pelanggan dan riwayat transaksi ---

    public function test_admin_can_view_customer_list(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['name' => 'Siti Customer', 'role' => 'customer']);
        Order::factory()->count(2)->create(['user_id' => $customer->id]);

        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->assertOk()
            ->assertSee('Siti Customer');

        $this->assertSame(2, Order::where('user_id', $customer->id)->count());
    }

    public function test_admin_can_search_customer_list_by_name_or_email(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'customer']);
        User::factory()->create(['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'role' => 'customer']);

        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->set('tableSearch', 'Budi')
            ->assertSee('Budi Santoso')
            ->assertDontSee('Dewi Lestari');
    }

    public function test_admin_can_view_a_customers_transaction_history_on_their_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['name' => 'Siti Customer', 'role' => 'customer']);
        $otherCustomer = User::factory()->create(['name' => 'Rudi Hartono', 'role' => 'customer']);

        $ownOrder = Order::factory()->create([
            'user_id' => $customer->id,
            'code' => 'ORD-SITI0001',
            'grand_total' => 750000,
            'payment_status' => 'paid',
            'order_status' => 'shipped',
        ]);

        $othersOrder = Order::factory()->create([
            'user_id' => $otherCustomer->id,
            'code' => 'ORD-RUDI0001',
        ]);

        $this->actingAs($admin);

        // The customer's edit page itself opens successfully.
        Livewire::test(EditUser::class, ['record' => $customer->getRouteKey()])
            ->assertOk();

        // Its "Riwayat Transaksi" relation manager only shows that
        // customer's own orders, not other customers' orders.
        Livewire::test(OrdersRelationManager::class, [
            'ownerRecord' => $customer,
            'pageClass' => EditUser::class,
        ])
            ->assertOk()
            ->assertSee('ORD-SITI0001')
            ->assertDontSee('ORD-RUDI0001');
    }

    public function test_orders_relation_manager_is_read_only_and_has_no_create_action(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['role' => 'customer']);
        Order::factory()->create(['user_id' => $customer->id, 'code' => 'ORD-READ0001']);

        $this->actingAs($admin);

        Livewire::test(OrdersRelationManager::class, [
            'ownerRecord' => $customer,
            'pageClass' => EditUser::class,
        ])
            ->assertOk()
            ->assertSee('ORD-READ0001')
            ->assertTableHeaderActionDoesNotExist('create');
    }

    // --- US-014: Melihat laporan penjualan ---

    public function test_admin_can_see_sales_stats_and_chart(): void
    {
        $admin = User::factory()->admin()->create();
        Order::factory()->paid()->create(['grand_total' => 1000000]);
        Order::factory()->create(['order_status' => 'new']);

        $this->actingAs($admin);

        Livewire::test(StatsOverview::class)
            ->assertOk()
            ->assertSee('Total Pemasukan (Paid)')
            ->assertSee('Pesanan Baru');

        Livewire::test(SalesChart::class)->assertOk();
    }
}
