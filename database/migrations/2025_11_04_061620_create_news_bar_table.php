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
        // The `news_bar` table will now store just settings such as speed, direction, etc.
        Schema::create('news_bar', function (Blueprint $table) {
            $table->id();
            $table->integer('speed')->default(120); // speed of the news bar
            $table->string('direction')->default('rtl'); // scrolling direction: 'rtl' or 'ltr'
            $table->boolean('pause_on_hover')->default(true); // Pause on hover or not
            $table->string('theme')->default('dark'); // Theme of the bar
            $table->integer('item_space')->default(40); // Space in pixels between items
            $table->timestamps();
        });

        // Continue defining `news_bar_items` table
        Schema::create('news_bar_items', function (Blueprint $table) {
            $table->id();
            $table->text('text'); // The news item text to appear in the news bar
            $table->boolean('active')->default(true); // Show/hide this item
            $table->integer('order')->default(0); // The display order of the item
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_bar_items');
        Schema::dropIfExists('news_bar');
    }
};
