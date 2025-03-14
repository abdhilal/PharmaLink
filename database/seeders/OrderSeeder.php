<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $pharmacy = User::where('role', 'pharmacy')->first();
        $warehouse = Warehouse::first();
        $medicineX = Medicine::where('name', 'Medicine X')->first();
        $medicineY = Medicine::where('name', 'Medicine Y')->first();

        if ($pharmacy && $warehouse && $medicineX && $medicineY) {
            $order = Order::create([
                'pharmacy_id' => $pharmacy->id,
                'warehouse_id' => $warehouse->id,
                'status' => 'pending',
                'total_price' => 0,
            ]);

            $subtotalX = $medicineX->price * 12; // 12 قطعة
            if ($medicineX->offer && str_contains($medicineX->offer, '10% off on 10+ units')) {
                $subtotalX *= 0.9; // تطبيق الخصم
            }

            OrderItem::create([
                'order_id' => $order->id,
                'medicine_id' => $medicineX->id,
                'quantity' => 12,
                'price_per_unit' => $medicineX->price,
                'subtotal' => $subtotalX,
            ]);

            $subtotalY = $medicineY->price * 5; // 5 قطع
            OrderItem::create([
                'order_id' => $order->id,
                'medicine_id' => $medicineY->id,
                'quantity' => 5,
                'price_per_unit' => $medicineY->price,
                'subtotal' => $subtotalY,
            ]);

            $order->total_price = $subtotalX + $subtotalY;
            $order->save();

            // تحديث الكميات في المستودع
            $medicineX->quantity -= 12;
            $medicineX->save();
            $medicineY->quantity -= 5;
            $medicineY->save();
        }
    }
}