<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\City;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Medicine;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{


    public function index()
    {
        // موقع الصيدلية الحالية
        if($pharmacy = auth()->user()->city){


        $pharmacy = auth()->user()->city; // من خلال العلاقة hasOne في نموذج User
        $pharmacyLat = $pharmacy->latitude;
        $pharmacyLon = $pharmacy->longitude;

        // جلب المستخدمين الذين لديهم دور "warehouse" مع المستودعات والمدن
        $users = User::where('role', 'warehouse')
            ->where('id', '!=', auth()->id()) // استثناء الصيدلية الحالية
            ->with(['warehouse', 'city']) // جلب العلاقات
            ->get();

        // تصفية المستودعات بناءً على النطاق وحساب المسافة
        $warehouses = $users->filter(function ($user) use ($pharmacyLat, $pharmacyLon) {
            // التأكد من وجود مستودع ومدينة مع إحداثيات ونطاقات
            if (!$user->warehouse || !$user->city || !$user->city->latitude || !$user->city->longitude || !$user->city->range_east) {
                return false;
            }

            $warehouseLat = $user->city->latitude;
            $warehouseLon = $user->city->longitude;

            // حساب الفرق في الإحداثيات للتحقق من النطاق
            $latDiff = ($pharmacyLat - $warehouseLat) * 111;
            $lonDiff = ($pharmacyLon - $warehouseLon) * 111 * cos(deg2rad($warehouseLat));

            // التحقق من النطاق
            $isWithinRange = ($lonDiff >= -$user->city->range_west && $lonDiff <= $user->city->range_east) &&
                ($latDiff >= -$user->city->range_south && $latDiff <= $user->city->range_north);

            if ($isWithinRange) {
                // حساب المسافة باستخدام معادلة Haversine
                $earthRadius = 6371; // نصف قطر الأرض بالكيلومترات
                $latDiffRad = deg2rad($pharmacyLat - $warehouseLat);
                $lonDiffRad = deg2rad($pharmacyLon - $warehouseLon);

                $a = sin($latDiffRad / 2) * sin($latDiffRad / 2) +
                    cos(deg2rad($warehouseLat)) * cos(deg2rad($pharmacyLat)) *
                    sin($lonDiffRad / 2) * sin($lonDiffRad / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $distance = $earthRadius * $c;

                // إضافة المسافة كخاصية ديناميكية للمستودع
                $user->city->distance = round($distance, 2);
                return true;
            }
            return false;
        })->map(function ($user) {
            return $user; // إرجاع المستودع فقط للواجهة
        });
        return view('pharmacy.warehouses.index', compact('warehouses'));

    }
    $warehouses=[];
    return view('pharmacy.warehouses.index', compact('warehouses'));

    }


    public function show($warehouseId)
    {

        $medicines = Medicine::where('warehouse_id', $warehouseId)
            ->with('company')
            ->orderBy('company_id') // ترتيب حسب الشركة أولاً
            ->get()
            ->groupBy('company.name'); // تجميع حسب اسم الشركة


        $offer = Offer::where('warehouse_id', $warehouseId)->latest()->first();

        return view('pharmacy.warehouses.show', compact('medicines', 'offer', 'warehouseId'));
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
            'warehouse',
            'pendingOrders',
            'deliveredOrders',
            'totalSales',
            'latestOrders',
            'pharmaciesCount',
            'totalDebt',
            'capital',
            'expiringMedicines',
            'lowStockMedicines'
        ));
    }
}
