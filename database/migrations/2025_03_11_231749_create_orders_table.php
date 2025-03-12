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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // معرف الطلب
            $table->foreignId('pharmacy_id')->constrained('users')->onDelete('cascade'); // معرف الصيدلية
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // معرف المستودع
            $table->enum('status', ['pending', 'ready', 'delivered'])->default('pending'); // حالة الطلب (معلق، جاهز، تم التوصيل)
            $table->decimal('total_price', 8, 2); // السعر الإجمالي
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
