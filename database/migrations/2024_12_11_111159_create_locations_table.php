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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('address');
            $table->string('subtitle');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('image');
            $table->string('information');
            $table->string('map_image');
            $table->string('map_url');
            $table->integer('points');
            $table->string('puzzle_image');

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
