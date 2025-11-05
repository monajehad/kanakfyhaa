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
        Schema::create('artifacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landmark_id')->constrained('landmarks')->cascadeOnDelete();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();       
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artifacts');
    }
};
