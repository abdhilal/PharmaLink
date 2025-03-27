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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // معرف المعاملة
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade'); // معرف الحساب
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null'); // معرف الطلب (اختياري)
            $table->decimal('amount', 8, 2)->default(0.00); // قيمة المعاملة
            $table->enum('type', ['payment', 'debt']); // نوع المعاملة (دفع أو دين)
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
