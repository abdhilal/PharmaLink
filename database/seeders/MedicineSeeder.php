<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Medicine;
use App\Models\Warehouse;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        // إنشاء 5 شركات سورية
        $companies = [
            ['name' => 'شركة ابن سينا للأدوية'],
            ['name' => 'شركة المتحدة للصناعات الدوائية'],
            ['name' => 'شركة أدوية الشرق'],
            ['name' => 'شركة سما فارما'],
            ['name' => 'شركة الرازي للأدوية'],
        ];

        foreach ($companies as $companyData) {
            Company::create($companyData);
        }

        // افترض أن لديك 10 مستودعات من السكربت السابق (UserSeeder)
        $warehouses = Warehouse::all()->pluck('id')->toArray();

        // إنشاء 50 دواءً سوريًا بأسعار بالدولار (من 0.50 إلى 5.00)
        $medicines = [
            // شركة ابن سينا (10 أدوية)
            ['name' => 'باراسيتامول 500', 'company_id' => 1, 'price' => 0.75, 'quantity' => 100, 'barcode' => '963001', 'date' => '2026-04-01'],
            ['name' => 'أموكسيسيلين 250', 'company_id' => 1, 'price' => 1.20, 'quantity' => 50, 'barcode' => '963002', 'date' => '2025-12-01'],
            ['name' => 'إيبوبروفين 400', 'company_id' => 1, 'price' => 0.90, 'quantity' => 75, 'barcode' => '963003', 'date' => '2026-06-01'],
            ['name' => 'سيتريزين 10', 'company_id' => 1, 'price' => 0.80, 'quantity' => 60, 'barcode' => '963004', 'date' => '2025-10-01'],
            ['name' => 'أوميبرازول 20', 'company_id' => 1, 'price' => 1.50, 'quantity' => 40, 'barcode' => '963005', 'date' => '2026-03-01'],
            ['name' => 'ديكلوفيناك 50', 'company_id' => 1, 'price' => 1.00, 'quantity' => 80, 'barcode' => '963006', 'date' => '2025-11-01'],
            ['name' => 'لوراتادين 10', 'company_id' => 1, 'price' => 0.85, 'quantity' => 90, 'barcode' => '963007', 'date' => '2026-05-01'],
            ['name' => 'ميترونيدازول 250', 'company_id' => 1, 'price' => 0.70, 'quantity' => 70, 'barcode' => '963008', 'date' => '2025-09-01'],
            ['name' => 'سفالكسين 500', 'company_id' => 1, 'price' => 1.30, 'quantity' => 55, 'barcode' => '963009', 'date' => '2026-02-01'],
            ['name' => 'أزيترومايسين 250', 'company_id' => 1, 'price' => 2.00, 'quantity' => 45, 'barcode' => '963010', 'date' => '2025-08-01'],

            // شركة المتحدة (10 أدوية)
            ['name' => 'أسبرين 81', 'company_id' => 2, 'price' => 0.50, 'quantity' => 120, 'barcode' => '963011', 'date' => '2026-07-01'],
            ['name' => 'كلوبيدوغريل 75', 'company_id' => 2, 'price' => 2.50, 'quantity' => 30, 'barcode' => '963012', 'date' => '2025-12-01'],
            ['name' => 'أتورفاستاتين 20', 'company_id' => 2, 'price' => 1.80, 'quantity' => 50, 'barcode' => '963013', 'date' => '2026-04-01'],
            ['name' => 'ميتفورمين 500', 'company_id' => 2, 'price' => 0.95, 'quantity' => 60, 'barcode' => '963014', 'date' => '2025-11-01'],
            ['name' => 'لوسارتان 50', 'company_id' => 2, 'price' => 1.40, 'quantity' => 70, 'barcode' => '963015', 'date' => '2026-03-01'],
            ['name' => 'أملوديبين 5', 'company_id' => 2, 'price' => 1.10, 'quantity' => 80, 'barcode' => '963016', 'date' => '2025-10-01'],
            ['name' => 'بانتوبرازول 40', 'company_id' => 2, 'price' => 1.60, 'quantity' => 45, 'barcode' => '963017', 'date' => '2026-06-01'],
            ['name' => 'فيتامين د3 5000', 'company_id' => 2, 'price' => 0.65, 'quantity' => 100, 'barcode' => '963018', 'date' => '2025-09-01'],
            ['name' => 'كالسيوم 600', 'company_id' => 2, 'price' => 0.80, 'quantity' => 90, 'barcode' => '963019', 'date' => '2026-02-01'],
            ['name' => 'فوليك أسيد 5', 'company_id' => 2, 'price' => 0.55, 'quantity' => 110, 'barcode' => '963020', 'date' => '2025-08-01'],

            // شركة أدوية الشرق (10 أدوية)
            ['name' => 'رانيتيدين 150', 'company_id' => 3, 'price' => 0.70, 'quantity' => 85, 'barcode' => '963021', 'date' => '2026-05-01'],
            ['name' => 'سالبوتامول 4', 'company_id' => 3, 'price' => 0.85, 'quantity' => 65, 'barcode' => '963022', 'date' => '2025-12-01'],
            ['name' => 'بوديزونيد 200', 'company_id' => 3, 'price' => 2.20, 'quantity' => 40, 'barcode' => '963023', 'date' => '2026-03-01'],
            ['name' => 'ليفوثيروكسين 100', 'company_id' => 3, 'price' => 1.30, 'quantity' => 55, 'barcode' => '963024', 'date' => '2025-11-01'],
            ['name' => 'هيدروكلوروثيازيد 25', 'company_id' => 3, 'price' => 0.90, 'quantity' => 70, 'barcode' => '963025', 'date' => '2026-04-01'],
            ['name' => 'فينلافاكسين 75', 'company_id' => 3, 'price' => 1.80, 'quantity' => 50, 'barcode' => '963026', 'date' => '2025-10-01'],
            ['name' => 'ترامادول 50', 'company_id' => 3, 'price' => 1.10, 'quantity' => 60, 'barcode' => '963027', 'date' => '2026-06-01'],
            ['name' => 'كلونازيبام 2', 'company_id' => 3, 'price' => 1.50, 'quantity' => 45, 'barcode' => '963028', 'date' => '2025-09-01'],
            ['name' => 'سيروكويل 100', 'company_id' => 3, 'price' => 2.40, 'quantity' => 35, 'barcode' => '963029', 'date' => '2026-02-01'],
            ['name' => 'دولوكستين 30', 'company_id' => 3, 'price' => 2.00, 'quantity' => 50, 'barcode' => '963030', 'date' => '2025-08-01'],

            // شركة سما فارما (10 أدوية)
            ['name' => 'كابتوبريل 25', 'company_id' => 4, 'price' => 0.95, 'quantity' => 75, 'barcode' => '963031', 'date' => '2026-05-01'],
            ['name' => 'إنالابريل 10', 'company_id' => 4, 'price' => 1.10, 'quantity' => 65, 'barcode' => '963032', 'date' => '2025-12-01'],
            ['name' => 'فوروسيميد 40', 'company_id' => 4, 'price' => 0.80, 'quantity' => 80, 'barcode' => '963033', 'date' => '2026-03-01'],
            ['name' => 'سبيرونولاكتون 25', 'company_id' => 4, 'price' => 0.90, 'quantity' => 70, 'barcode' => '963034', 'date' => '2025-11-01'],
            ['name' => 'ديجوكسين 0.25', 'company_id' => 4, 'price' => 1.20, 'quantity' => 60, 'barcode' => '963035', 'date' => '2026-04-01'],
            ['name' => 'وارفارين 5', 'company_id' => 4, 'price' => 1.30, 'quantity' => 55, 'barcode' => '963036', 'date' => '2025-10-01'],
            ['name' => 'بروبرانولول 40', 'company_id' => 4, 'price' => 0.85, 'quantity' => 85, 'barcode' => '963037', 'date' => '2026-06-01'],
            ['name' => 'أتينولول 50', 'company_id' => 4, 'price' => 1.00, 'quantity' => 70, 'barcode' => '963038', 'date' => '2025-09-01'],
            ['name' => 'كارفيديلول 6.25', 'company_id' => 4, 'price' => 1.50, 'quantity' => 50, 'barcode' => '963039', 'date' => '2026-02-01'],
            ['name' => 'نيتروغليسرين 0.5', 'company_id' => 4, 'price' => 1.20, 'quantity' => 60, 'barcode' => '963040', 'date' => '2025-08-01'],

            // شركة الرازي (10 أدوية)
            ['name' => 'ليفوسيتريزين 5', 'company_id' => 5, 'price' => 0.90, 'quantity' => 80, 'barcode' => '963041', 'date' => '2026-05-01'],
            ['name' => 'مونتيلوكاست 10', 'company_id' => 5, 'price' => 1.80, 'quantity' => 50, 'barcode' => '963042', 'date' => '2025-12-01'],
            ['name' => 'فيكسوفينادين 180', 'company_id' => 5, 'price' => 1.50, 'quantity' => 60, 'barcode' => '963043', 'date' => '2026-03-01'],
            ['name' => 'ديزلوراتادين 5', 'company_id' => 5, 'price' => 1.20, 'quantity' => 70, 'barcode' => '963044', 'date' => '2025-11-01'],
            ['name' => 'كلاريتين 10', 'company_id' => 5, 'price' => 1.10, 'quantity' => 75, 'barcode' => '963045', 'date' => '2026-04-01'],
            ['name' => 'سيبروفلوكساسين 500', 'company_id' => 5, 'price' => 1.60, 'quantity' => 55, 'barcode' => '963046', 'date' => '2025-10-01'],
            ['name' => 'ليفوفلوكساسين 750', 'company_id' => 5, 'price' => 2.50, 'quantity' => 40, 'barcode' => '963047', 'date' => '2026-06-01'],
            ['name' => 'نورفلوكساسين 400', 'company_id' => 5, 'price' => 1.40, 'quantity' => 60, 'barcode' => '963048', 'date' => '2025-09-01'],
            ['name' => 'أوفلوكساسين 200', 'company_id' => 5, 'price' => 1.20, 'quantity' => 70, 'barcode' => '963049', 'date' => '2026-02-01'],
            ['name' => 'موكسيفلوكساسين 400', 'company_id' => 5, 'price' => 3.00, 'quantity' => 45, 'barcode' => '963050', 'date' => '2025-08-01'],
        ];

        foreach ($medicines as $medicineData) {
            Medicine::create([
                'warehouse_id' => $warehouses[array_rand($warehouses)], // اختيار مستودع عشوائي
                'company_id' => $medicineData['company_id'],
                'name' => $medicineData['name'],
                'price' => $medicineData['price'],
                'quantity' => $medicineData['quantity'],
                'date' => $medicineData['date'],
                'barcode' => $medicineData['barcode'],
                'offer' => null, // يمكنك إضافة عروض لاحقًا
                'discount_percentage' => null,
                'profit_percentage' => 20.00, // نسبة ربح افتراضية
                'selling_price' => $medicineData['price'] * 1.20, // سعر البيع = السعر + 20%
                'is_hidden' => 0,
            ]);
        }
    }
}
