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
            $table->foreignId('city_id')->nullable()->constrained('cities')->cascadeOnDelete();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('title')->nullable();
            $table->string('short_description', 255)->nullable();
            $table->longText('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('color')->nullable();
            $table->json('colors')->nullable(); // Array of color hex codes
            $table->json('sizes')->nullable(); // ["S","M","L","XL"]
            $table->decimal('price_cost', 10, 2)->default(0);
            $table->decimal('price_sell', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->nullable(); // Simple price field
            $table->decimal('discount', 5, 2)->default(0);
            $table->uuid('uuid')->unique();
            $table->string('qr_code')->nullable();
            $table->string('image')->nullable(); // Image URL
            $table->boolean('published')->default(false);
            $table->boolean('is_package')->default(false);
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
