<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Company;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Warehouse;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        // إنشاء مدن
        $cities = ['Riyadh', 'Jeddah', 'Dammam'];
        foreach ($cities as $cityName) {
            City::firstOrCreate(['name' => $cityName]);
        }

        // إنشاء شركات
        $companies = ['Company A', 'Company B'];
        foreach ($companies as $companyName) {
            Company::firstOrCreate(['name' => $companyName]);
        }

        // إضافة بيانات لمستودع موجود
        $warehouse = Warehouse::first();
        if ($warehouse) {
            $warehouse->cities()->sync(City::pluck('id')); // ربط المستودع بجميع المدن

            Medicine::create([
                'warehouse_id' => $warehouse->id,
                'company_id' => Company::where('name', 'Company A')->first()->id,
                'name' => 'Medicine X',
                'price' => 10.00,
                'quantity' => 50,
                'offer' => '10% off on 10+ units',
            ]);

            Medicine::create([
                'warehouse_id' => $warehouse->id,
                'company_id' => Company::where('name', 'Company B')->first()->id,
                'name' => 'Medicine Y',
                'price' => 15.00,
                'quantity' => 30,
            ]);
        }
    }
}