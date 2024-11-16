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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Chazzle');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('address')->default('26985 Brighton Lane, Lake Forest, CA 92630');
            $table->longText('description');
            $table->string('email')->default('example@example.com');
            $table->string('phone')->default('+8801 123 456 789');
            $table->string('office_time')->default('10:00 - 18:00');
            $table->string('footer_text')->default('Lorem Ipsam Doller');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
