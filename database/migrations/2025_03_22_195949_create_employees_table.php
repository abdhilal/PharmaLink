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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // ربط بالمستودع
            $table->string('name'); // اسم الموظف
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('position'); // المنصب (مثل: مندوب، محاسب، مدير)
            $table->decimal('salary', 8, 2); // الراتب الأساسي
            $table->date('date'); // تاريخ التوظيف
            $table->enum('status', ['active', 'inactive'])->default('active'); // حالة الموظف
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
