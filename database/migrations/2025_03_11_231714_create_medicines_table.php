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
            $table->string('offer')->nullable(); // العرض الخاص (اختياري)
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
