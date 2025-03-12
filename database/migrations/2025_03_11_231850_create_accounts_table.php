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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // معرف الحساب
            $table->foreignId('pharmacy_id')->constrained('users')->onDelete('cascade'); // معرف الصيدلية
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // معرف المستودع
            $table->decimal('balance', 8, 2)->default(0); // الرصيد الحالي
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
