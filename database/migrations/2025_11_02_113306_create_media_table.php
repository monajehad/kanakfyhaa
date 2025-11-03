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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mediable_id');
            $table->string('mediable_type');
            $table->enum('type', ['image', 'video']);
            $table->enum('role', ['main', 'sub'])->default('sub')->comment('Defines if the file is the main file or a supplementary (sub) file. For example: image and sub images');
            $table->string('url');
            $table->string('thumbnail')->nullable();
            $table->string('alt_text')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['mediable_id', 'mediable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
