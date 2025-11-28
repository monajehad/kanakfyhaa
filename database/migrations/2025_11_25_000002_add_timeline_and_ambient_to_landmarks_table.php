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
        // Add timeline and ambient fields to landmarks
        Schema::table('landmarks', function (Blueprint $table) {
            $table->text('ambient_description')->nullable()->after('description_en');
            $table->text('ambient_description_ar')->nullable()->after('ambient_description');
            $table->text('ambient_description_en')->nullable()->after('ambient_description_ar');
            $table->json('timeline')->nullable()->after('ambient_description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->dropColumn([
                'ambient_description',
                'ambient_description_ar',
                'ambient_description_en',
                'timeline',
            ]);
        });
    }
};
