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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id(); // معرف الدواء
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // معرف المستودع
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // معرف الشركة المصنعة
            $table->string('name'); // اسم الدواء
            $table->decimal('price', 8, 2); // سعر الدواء
            $table->integer('quantity'); // الكمية المتوفرة
            $table->date('date')->nullable(); // تاريخ انتهاء الصلاحية
            $table->string('barcode')->unique()->nullable(); // الباركود (فريد)
            $table->string('offer')->nullable(); // العرض الخاص (اختياري)
            $table->decimal('discount_percentage', 5, 2)->nullable(); // نسبة الحسم
            $table->decimal('profit_percentage', 5, 2)->nullable(); // نسبة الربح
            $table->decimal('selling_price', 8, 2)->nullable(); // سعر البيع
            $table->boolean('is_hidden')->default(0); // افتراضيًا الدواء مرئي
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
