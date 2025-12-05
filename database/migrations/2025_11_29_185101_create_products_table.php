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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 15, 2);
        
        // Spesifikasi Teknis
        $table->decimal('weight_kg', 8, 2); 
        $table->decimal('length_cm', 8, 2)->nullable();
        $table->decimal('width_cm', 8, 2)->nullable();
        $table->decimal('height_cm', 8, 2)->nullable();
        
        // Atribut Visual
        $table->string('material')->nullable();
        $table->string('finishing')->nullable();
        $table->string('color')->nullable();
        
        // Stok & Status
        $table->integer('stock')->default(0);
        $table->enum('availability', ['ready', 'pre_order', 'out_of_stock'])->default('ready');
        $table->boolean('is_active')->default(true);
        $table->boolean('is_featured')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
