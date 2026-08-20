<?php

namespace Tests\Feature;

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CouponResource\Pages\CreateCoupon;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\ReviewResource\Pages\ListReviews;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_panel_access_is_role_restricted(): void
    {
        $customer = $this->customerUser();

        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();

        $this->actingAs($customer)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_see_resource_lists(): void
    {
        $admin = $this->adminUser();
        $product = $this->product(['name' => 'Admin Visible Product']);

        $this->actingAs($admin);

        Livewire::test(ListProducts::class)
            ->assertCanSeeTableRecords([$product]);

        Livewire::test(ListCategories::class)
            ->assertCanSeeTableRecords([$product->categories()->first()]);
    }

    public function test_category_create_validation_and_success(): void
    {
        $this->actingAs($this->adminUser());

        Livewire::test(CreateCategory::class)
            ->call('create')
            ->assertHasFormErrors(['name' => 'required'])
            ->fillForm(['name' => 'Dining Room'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Dining Room',
            'slug' => 'dining-room',
        ]);
    }

    public function test_coupon_create_validation_uppercases_code_and_persists_data(): void
    {
        $this->actingAs($this->adminUser());

        Livewire::test(CreateCoupon::class)
            ->call('create')
            ->assertHasFormErrors([
                'code' => 'required',
                'value' => 'required',
            ])
            ->fillForm([
                'code' => 'save10',
                'type' => 'percent',
                'value' => 10,
                'min_spend' => 100000,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('coupons', [
            'code' => 'SAVE10',
            'type' => 'percent',
            'value' => 10,
            'min_spend' => 100000,
            'is_active' => true,
        ]);
    }

    public function test_user_create_validation_and_password_hashing(): void
    {
        $this->actingAs($this->adminUser());

        Livewire::test(CreateUser::class)
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ])
            ->fillForm([
                'name' => 'Created Admin',
                'email' => 'created-admin@example.com',
                'role' => 'admin',
                'password' => 'secret-password',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::where('email', 'created-admin@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('admin', $user->role);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    public function test_product_create_form_reports_required_field_errors(): void
    {
        $this->actingAs($this->adminUser());

        Livewire::test(CreateProduct::class)
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'slug' => 'required',
                'price' => 'required',
                'weight_kg' => 'required',
            ]);
    }

    public function test_admin_can_approve_review_from_table_action(): void
    {
        $this->actingAs($this->adminUser());

        $review = Review::factory()->create([
            'user_id' => $this->customerUser()->id,
            'product_id' => $this->product()->id,
            'is_approved' => false,
        ]);

        Livewire::test(ListReviews::class)
            ->callTableAction('approve', $review);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'is_approved' => true,
        ]);
    }

    public function test_order_table_actions_update_shipping_quote_and_tracking_number(): void
    {
        $this->actingAs($this->adminUser());

        $waitingQuote = $this->orderFor($this->customerUser(), [
            'order_status' => 'waiting_quote',
            'total_product_price' => 2000000,
            'discount_amount' => 100000,
            'grand_total' => 1900000,
        ]);

        Livewire::test(ListOrders::class)
            ->callTableAction('input_ongkir', $waitingQuote, [
                'shipping_price' => 250000,
                'notes' => 'Ongkir sudah dihitung.',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $waitingQuote->id,
            'shipping_price' => 250000,
            'grand_total' => 2150000,
            'order_status' => 'waiting_payment',
            'notes' => 'Ongkir sudah dihitung.',
        ]);

        $processingOrder = Order::factory()->for($this->customerUser())->create([
            'order_status' => 'processing',
            'payment_status' => 'paid',
            'total_product_price' => 2000000,
            'grand_total' => 2000000,
        ]);

        Livewire::test(ListOrders::class)
            ->callTableAction('update_resi', $processingOrder, [
                'tracking_number' => 'JNE123456',
                'shipping_courier' => 'JNE Trucking',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $processingOrder->id,
            'tracking_number' => 'JNE123456',
            'shipping_courier' => 'JNE Trucking',
            'order_status' => 'shipped',
        ]);
    }
}
