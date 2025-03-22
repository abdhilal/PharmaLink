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
        Schema::create('warehouse_cashes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->enum('transaction_type', ['income', 'expense']); // نوع الحركة: دخل أو مصروف
            $table->decimal('amount', 8, 2); // المبلغ
            $table->string('description', 255); // الوصف (مثل: "مصروف إيجار"، "دفعة طلبية")
            $table->date('date'); // تاريخ الحركة
            $table->unsignedBigInteger('related_id')->nullable(); // معرف العنصر المرتبط (اختياري)
            $table->string('related_type')->nullable(); // نوع العنصر المرتبط (مثل: expense)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_cash');
    }
};
