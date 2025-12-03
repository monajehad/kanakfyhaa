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
        // Add multilingual fields to countries table
        Schema::table('countries', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->string('name_en')->nullable()->after('name_ar');
            $table->string('short_description')->nullable()->after('area');
            $table->string('short_description_ar')->nullable()->after('short_description');
            $table->string('short_description_en')->nullable()->after('short_description_ar');
            $table->text('description')->nullable()->after('short_description_en');
            $table->text('description_ar')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_ar');
        });

        // Add short description fields to cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('description_en');
            $table->string('short_description_ar')->nullable()->after('short_description');
            $table->string('short_description_en')->nullable()->after('short_description_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar',
                'name_en',
                'short_description',
                'short_description_ar',
                'short_description_en',
                'description',
                'description_ar',
                'description_en'
            ]);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'short_description_ar',
                'short_description_en'
            ]);
        });
    }
};
