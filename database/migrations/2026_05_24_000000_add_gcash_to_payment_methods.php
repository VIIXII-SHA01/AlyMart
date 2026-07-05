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
        // For MySQL, we can use change() to modify the column
        Schema::table('sales', function (Blueprint $table) {
            // Change payment_method enum to include 'gcash'
            $table->enum('payment_method', ['cash', 'card', 'gcash', 'other'])->default('cash')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Revert payment_method enum to original values
            $table->enum('payment_method', ['cash', 'card', 'other'])->default('cash')->change();
        });
    }
};
