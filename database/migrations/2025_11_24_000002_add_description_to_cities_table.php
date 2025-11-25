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
        Schema::table('cities', function (Blueprint $table) {
            // Check if columns don't already exist before adding them
            if (!Schema::hasColumn('cities', 'description')) {
                $table->text('description')->nullable()->after('name_en');
            }
            if (!Schema::hasColumn('cities', 'description_ar')) {
                $table->text('description_ar')->nullable()->after('description');
            }
            if (!Schema::hasColumn('cities', 'description_en')) {
                $table->text('description_en')->nullable()->after('description_ar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['description', 'description_ar', 'description_en']);
        });
    }
};
