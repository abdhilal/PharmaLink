<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Order;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $pharmacy = User::where('role', 'pharmacy')->first();
        $warehouse = Warehouse::first();
        $order = Order::first();

        if ($pharmacy && $warehouse && $order) {
            // تغيير حالة الطلبية إلى delivered
            $order->status = 'delivered';
            $order->save();

            // إنشاء حساب
            $account = Account::create([
                'pharmacy_id' => $pharmacy->id,
                'warehouse_id' => $warehouse->id,
                'balance' => 0,
            ]);

            // تسجيل دين
            Transaction::create([
                'account_id' => $account->id,
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'type' => 'debt',
            ]);
            $account->balance += $order->total_price; // 183.00
            $account->save();

            // تسجيل دفع جزئي
            Transaction::create([
                'account_id' => $account->id,
                'order_id' => $order->id,
                'amount' => 100.00,
                'type' => 'payment',
            ]);
            $account->balance -= 100.00; // الرصيد الآن 83.00
            $account->save();
        }
    }
}