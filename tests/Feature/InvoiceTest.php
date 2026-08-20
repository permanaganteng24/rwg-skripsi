<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_owner_and_admin_can_download_invoice(): void
    {
        $owner = $this->customerUser();
        $admin = $this->adminUser();
        $order = $this->orderFor($owner, ['code' => 'ORD-INVOICE-001']);

        $this->actingAs($owner)
            ->get('/invoice/' . $order->id)
            ->assertOk();

        $this->actingAs($admin)
            ->get('/invoice/' . $order->id)
            ->assertOk();
    }

    public function test_other_customer_cannot_download_invoice(): void
    {
        $owner = $this->customerUser();
        $other = $this->customerUser();
        $order = $this->orderFor($owner);

        $this->actingAs($other)
            ->get('/invoice/' . $order->id)
            ->assertForbidden();
    }
}
