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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // null for system-wide notifications
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['low_stock', 'out_of_stock', 'system', 'info', 'warning', 'success']);
            $table->boolean('is_read')->default(false);
            $table->string('related_type')->nullable(); // e.g., 'product', 'sale'
            $table->integer('related_id')->nullable(); // ID of related record
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
