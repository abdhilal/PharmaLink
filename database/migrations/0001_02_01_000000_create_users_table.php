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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // معرف المستخدم
            $table->string('name'); // اسم المستخدم
            $table->string('email')->unique(); // البريد الإلكتروني (فريد)
            $table->timestamp('email_verified_at')->nullable(); // تاريخ تأكيد البريد الإلكتروني
            $table->string('password'); // كلمة المرور
            $table->enum('role', ['pharmacy', 'warehouse']); // نوع المستخدم (صيدلية أو مستودع)
            $table->rememberToken(); // رمز تذكر تسجيل الدخول
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
