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
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('notes')->nullable();
            $table->json('items');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency_symbol', 5)->default('$');
            $table->decimal('currency_rate', 10, 4)->default(1);
            $table->string('payment_method')->nullable(); // paypal | stripe
            $table->string('payment_status')->default('pending'); // pending | paid | failed | refunded
            $table->string('order_status')->default('processing'); // processing | shipped | delivered | cancelled
            $table->string('transaction_id')->nullable();
            $table->string('payer_email')->nullable();
            $table->timestamp('order_date')->useCurrent();
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


