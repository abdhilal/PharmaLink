<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseCash;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 10 صيدليات
        $pharmacies = [
            ['name' => 'صيدلية الرحمة', 'email' => 'pharmacy1@gmail.com', 'city' => 'دمشق - المزة', 'lat' => 33.5102, 'long' => 36.2913],
            ['name' => 'صيدلية النور', 'email' => 'pharmacy2@gmail.com', 'city' => 'حلب - الجميلية', 'lat' => 36.5864, 'long' => 37.0372],
            ['name' => 'صيدلية الشفاء', 'email' => 'pharmacy3@gmail.com', 'city' => 'حمص - الحميدية', 'lat' => 34.7324, 'long' => 36.7137],
            ['name' => 'صيدلية السعادة', 'email' => 'pharmacy4@gmail.com', 'city' => 'اللاذقية - الميناء', 'lat' => 35.5396, 'long' => 35.7831],
            ['name' => 'صيدلية الهدى', 'email' => 'pharmacy5@gmail.com', 'city' => 'طرطوس - الحمراء', 'lat' => 34.8890, 'long' => 35.8866],
            ['name' => 'صيدلية الامل', 'email' => 'pharmacy6@gmail.com', 'city' => 'دير الزور - الجورة', 'lat' => 35.3359, 'long' => 40.1406],
            ['name' => 'صيدلية الفرات', 'email' => 'pharmacy7@gmail.com', 'city' => 'الحسكة - المركز', 'lat' => 36.5024, 'long' => 40.7463],
            ['name' => 'صيدلية السلام', 'email' => 'pharmacy8@gmail.com', 'city' => 'القامشلي - حي الزهور', 'lat' => 37.0538, 'long' => 41.2144],
            ['name' => 'صيدلية البركة', 'email' => 'pharmacy9@gmail.com', 'city' => 'إدلب - المركز', 'lat' => 35.9306, 'long' => 36.6339],
            ['name' => 'صيدلية الحياة', 'email' => 'pharmacy10@gmail.com', 'city' => 'درعا - حي المطار', 'lat' => 32.6204, 'long' => 36.1058],
        ];

        foreach ($pharmacies as $pharmacyData) {
            $pharmacy = User::create([
                'name' => $pharmacyData['name'],
                'email' => $pharmacyData['email'],
                'password' => Hash::make('12345678'),
                'role' => 'pharmacy',
            ]);

            City::create([
                'user_id' => $pharmacy->id,
                'name' => $pharmacyData['city'],
                'latitude' => $pharmacyData['lat'],
                'longitude' => $pharmacyData['long'],
            ]);
        }

        // إنشاء 10 مستودعات
        $warehouses = [
            ['name' => 'مستودع الأمل للأدوية', 'email' => 'warehouse1@gmail.com', 'city' => 'دمشق - كفرسوسة', 'lat' => 33.5123, 'long' => 36.2954, 'phone' => '0933-123-456', 'address' => 'شارع الكواكب'],
            ['name' => 'مستودع الشام للأدوية', 'email' => 'warehouse2@gmail.com', 'city' => 'حلب - الصالحية', 'lat' => 36.5895, 'long' => 37.0401, 'phone' => '0933-234-567', 'address' => 'شارع الفرات'],
            ['name' => 'مستودع النور للأدوية', 'email' => 'warehouse3@gmail.com', 'city' => 'حمص - باب تدمر', 'lat' => 34.7356, 'long' => 36.7168, 'phone' => '0933-345-678', 'address' => 'شارع الشهداء'],
            ['name' => 'مستودع البحر للأدوية', 'email' => 'warehouse4@gmail.com', 'city' => 'اللاذقية - الرمل', 'lat' => 35.5427, 'long' => 35.7862, 'phone' => '0933-456-789', 'address' => 'شارع البحر'],
            ['name' => 'مستودع الجنوب للأدوية', 'email' => 'warehouse5@gmail.com', 'city' => 'طرطوس - الشيخ سعد', 'lat' => 34.8921, 'long' => 35.8897, 'phone' => '0933-567-890', 'address' => 'شارع الجنوب'],
            ['name' => 'مستودع الشرق للأدوية', 'email' => 'warehouse6@gmail.com', 'city' => 'دير الزور - القصور', 'lat' => 35.3390, 'long' => 40.1437, 'phone' => '0933-678-901', 'address' => 'شارع الشرق'],
            ['name' => 'مستودع السلام للأدوية', 'email' => 'warehouse7@gmail.com', 'city' => 'الحسكة - الصناعة', 'lat' => 36.5055, 'long' => 40.7494, 'phone' => '0933-789-012', 'address' => 'شارع الصناعة'],
            ['name' => 'مستودع الشمال للأدوية', 'email' => 'warehouse8@gmail.com', 'city' => 'القامشلي - الوحدة', 'lat' => 37.0569, 'long' => 41.2175, 'phone' => '0933-890-123', 'address' => 'شارع الشمال'],
            ['name' => 'مستودع الغرب للأدوية', 'email' => 'warehouse9@gmail.com', 'city' => 'إدلب - السوق', 'lat' => 35.9337, 'long' => 36.6370, 'phone' => '0933-901-234', 'address' => 'شارع الغرب'],
            ['name' => 'مستودع الريف للأدوية', 'email' => 'warehouse10@gmail.com', 'city' => 'درعا - النعيمة', 'lat' => 32.6235, 'long' => 36.1089, 'phone' => '0933-012-345', 'address' => 'شارع الريف'],
        ];

        foreach ($warehouses as $warehouseData) {
            $warehouseUser = User::create([
                'name' => $warehouseData['name'],
                'email' => $warehouseData['email'],
                'password' => Hash::make('12345678'),
                'role' => 'warehouse',
            ]);

            Warehouse::create([
                'user_id' => $warehouseUser->id,
                'phone' => $warehouseData['phone'],
                'address' => $warehouseData['address'],
            ]);

            City::create([
                'user_id' => $warehouseUser->id,
                'name' => $warehouseData['city'],
                'latitude' => $warehouseData['lat'],
                'longitude' => $warehouseData['long'],
                'range_east' => 1000,
                'range_west' => 1000,
                'range_north' => 1000,
                'range_south' => 1000,
            ]);
        }
    }
}
