<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;

class WarehouseController extends Controller
{


    public function index()
    {
        $warehouses = Warehouse::whereHas('cities', function ($query) {
            $query->where('city_id', auth()->user()->city_id);
        })->get();
        return view('warehouse.index', compact('warehouses'));
    }





        public function dashboard()
        {
            $warehouse = auth()->user()->warehouse;

            if (!$warehouse) {
                abort(404, 'لا يوجد مستودع مرتبط بهذا الحساب.');
            }

            // إحصائيات الطلبيات
            $pendingOrders = Order::where('warehouse_id', $warehouse->id)->where('status', 'pending')->count();
            $deliveredOrders = Order::where('warehouse_id', $warehouse->id)->where('status', 'delivered')->count();
            $totalSales = Order::where('warehouse_id', $warehouse->id)->where('status', 'delivered')->sum('total_price') ?? 0;
            $latestOrders = Order::where('warehouse_id', $warehouse->id)->latest()->take(5)->get();

            // إحصائيات الحسابات
            $pharmaciesCount = Account::where('warehouse_id', $warehouse->id)->count();
            $totalDebt = Account::where('warehouse_id', $warehouse->id)->sum('balance') ?? 0;

            return view('dashboard', compact(
                'warehouse', 'pendingOrders', 'deliveredOrders', 'totalSales', 'latestOrders',
                'pharmaciesCount', 'totalDebt'
            ));
        }

}
