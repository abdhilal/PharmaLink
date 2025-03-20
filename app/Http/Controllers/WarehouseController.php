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

        $warehouse->load([
            'orders' => function ($query) {
                $query->latest()->take(5);
            },
            'accounts',
            'medicines'
        ]);

        $orders = Order::where('warehouse_id', $warehouse->id)
                       ->selectRaw("
                           COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders,
                           COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_orders,
                           SUM(CASE WHEN status = 'delivered' THEN total_price ELSE 0 END) as total_sales
                       ")
                       ->first();

        $pendingOrders = $orders->pending_orders ?? 0;
        $deliveredOrders = $orders->delivered_orders ?? 0;
        $totalSales = $orders->total_sales ?? 0;
        $latestOrders = $warehouse->orders;

        $pharmaciesCount = $warehouse->accounts->count();
        $totalDebt = $warehouse->accounts->sum('balance') ?? 0; // إجمالي الديون

        $capital = $warehouse->medicines->sum(function ($medicine) {
            return $medicine->quantity * $medicine->price;
        });

        $expiringMedicines = $warehouse->medicines->filter(function ($medicine) {
            return $medicine->date && $medicine->date <= now()->addMonth();
        })->take(5);

        $lowStockMedicines = $warehouse->medicines->filter(function ($medicine) {
            return $medicine->quantity <= 10;
        })->take(5); // الأدوية منخفضة الكمية
      

        return view('dashboard', compact(
            'warehouse', 'pendingOrders', 'deliveredOrders', 'totalSales', 'latestOrders',
            'pharmaciesCount', 'totalDebt', 'capital', 'expiringMedicines', 'lowStockMedicines'
        ));
    }
}
