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
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('short_description', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('color')->nullable();
            $table->json('sizes')->nullable(); // ["S","M","L","XL"]
            $table->decimal('price_cost', 10, 2)->default(0);
            $table->decimal('price_sell', 10, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);
            $table->uuid('uuid')->unique();
            $table->string('qr_code')->nullable();
            $table->boolean('published')->default(false);
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
