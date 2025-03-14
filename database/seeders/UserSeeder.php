<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // إنشاء صيدلية
        $pharmacy = User::create([
            'name' => 'Pharmacy A',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'pharmacy',
        ]);

        // إنشاء مستودع
        $warehouseUser = User::create([
            'name' => 'Warehouse A',
            'email' => 'warehouse@example.com',
            'password' => Hash::make('password'),
            'role' => 'warehouse',
        ]);

        // ربط المستودع بحساب المستخدم
        Warehouse::create([
            'user_id' => $warehouseUser->id,
            'phone' => '123-456-7890',
            'address' => '123 Warehouse St.',
        ]);
    }
}