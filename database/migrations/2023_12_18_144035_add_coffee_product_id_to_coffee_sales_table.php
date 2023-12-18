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
        Schema::table('coffee_sales', function (Blueprint $table) {
            $table->foreignId('coffee_product_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_sales', function (Blueprint $table) {
            $table->dropColumn('coffee_product_id');
        });
    }
};
