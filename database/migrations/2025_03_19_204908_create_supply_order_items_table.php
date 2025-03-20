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
        Schema::create('supply_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0.00);
            $table->decimal('discount_amount', 8, 2)->default(0.00);
            $table->decimal('subtotal', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_order_items');
    }
};
