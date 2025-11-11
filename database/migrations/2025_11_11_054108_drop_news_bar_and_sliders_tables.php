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
        // Drop tables in correct order (child tables first)
        Schema::dropIfExists('news_bar_items');
        Schema::dropIfExists('news_bar');
        Schema::dropIfExists('sliders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables if needed to rollback
        // Note: This would require the original migration code
        // For now, leaving empty as rollback may not be needed
    }
};
