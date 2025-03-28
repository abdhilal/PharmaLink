<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الأدوار
        $pharmacyRole = Role::create(['name' => 'pharmacy']);
        $warehouseRole = Role::create(['name' => 'warehouse']);
        $staffRole = Role::create(['name' => 'warehouse_staff']); // دور الموظفين

        // إنشاء الصلاحيات
        Permission::create(['name' => 'create-order']);
        Permission::create(['name' => 'view-medicines']);
        Permission::create(['name' => 'create-urgent-order']);
        Permission::create(['name' => 'accept-urgent-order']);
        Permission::create(['name' => 'view-orders']);
        Permission::create(['name' => 'manage-medicines']);
        Permission::create(['name' => 'deliver-orders']); // صلاحية تسليم الطلبيات

        // تعيين الصلاحيات للأدوار
        $pharmacyRole->syncPermissions([
            'create-order', 'create-urgent-order', 'view-medicines', 'view-orders'
        ]);

        $warehouseRole->syncPermissions([
            'view-medicines', 'accept-urgent-order', 'view-orders', 'manage-medicines'
        ]);

        $staffRole->syncPermissions([
            'view-orders', 'deliver-orders' // صلاحيات محدودة للموظفين
        ]);
    }
}
