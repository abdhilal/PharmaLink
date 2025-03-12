<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // معرف عنصر الطلب
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // معرف الطلب
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade'); // معرف الدواء
            $table->integer('quantity'); // الكمية المطلوبة
            $table->decimal('price_per_unit', 8, 2); // سعر الوحدة
            $table->decimal('subtotal', 8, 2); // المجموع الفرعي
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
