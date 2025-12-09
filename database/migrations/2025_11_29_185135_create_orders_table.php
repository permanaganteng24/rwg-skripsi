<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('code')->unique();
            $table->string('shipping_name');
            $table->string('company_name')->nullable();
            $table->string('shipping_phone');
            $table->string('shipping_email')->nullable();

            // Address
            $table->text('shipping_address');
            $table->string('shipping_country')->default('Indonesia');
            $table->string('shipping_province')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_district')->nullable(); 
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_method')->nullable();
            $table->string('shipping_courier')->nullable();
            $table->string('tracking_number')->nullable();

            // Biaya
            $table->decimal('total_weight_kg', 8, 2)->default(0);
            $table->decimal('total_product_price', 15, 2);
            $table->decimal('shipping_price', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);

            // Status
            $table->string('order_status')->default('new');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
