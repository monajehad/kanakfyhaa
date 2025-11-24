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
        Schema::create('product_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name')->comment('Package name (e.g., "Basic", "Premium", "Deluxe")');
            $table->string('name_ar')->nullable()->comment('Package name in Arabic');
            $table->string('name_en')->nullable()->comment('Package name in English');
            $table->text('description')->nullable()->comment('Package description');
            $table->text('description_ar')->nullable()->comment('Package description in Arabic');
            $table->text('description_en')->nullable()->comment('Package description in English');
            $table->json('items')->comment('Items included in package (JSON array)');
            $table->decimal('price', 10, 2)->comment('Package price');
            $table->decimal('original_price', 10, 2)->nullable()->comment('Original price before discount');
            $table->integer('discount')->default(0)->comment('Discount percentage');
            $table->decimal('shipping_price', 10, 2)->default(0)->comment('Shipping cost for this package');
            $table->integer('quantity')->default(1)->comment('Available quantity');
            $table->boolean('is_active')->default(true)->comment('Whether package is active/available');
            $table->integer('order')->default(0)->comment('Display order');
            $table->timestamps();
            
            // Add index for faster queries
            $table->index('product_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packages');
    }
};
