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
        Schema::table('medicine_details', function (Blueprint $table) {
            $table->decimal('buying_price', 8, 2)->nullable()->after('price');
            $table->date('expiry_date')->nullable()->after('buying_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_details', function (Blueprint $table) {
            $table->dropColumn('buying_price', 'expiry_date');
        });
    }
};
