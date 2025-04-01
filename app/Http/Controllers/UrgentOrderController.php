<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\City;
use App\Models\Medicine;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UrgentOrder;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UrgentOrderController extends Controller
{

    public function index()
    {
        $orders = UrgentOrder::with('items', 'user.city')->where('status', 'pending')->get();
        // موقع الصيدلية الحالية

        if ($orders->isNotEmpty()) {


            foreach ($orders as $order) {
                if ($order->user->city == null) {
                    continue;
                }
                $pharmacy = $order->user->city; // من خلال العلاقة hasOne في نموذج User
                $pharmacyLat = $pharmacy->latitude;
                $pharmacyLon = $pharmacy->longitude;

                // جلب المستخدمين الذين لديهم دور "warehouse" مع المستودعات والمدن
                $users = User::where('role', 'warehouse')
                    ->with(['warehouse', 'city']) // جلب العلاقات
                    ->get();

                // تصفية المستودعات بناءً على النطاق وحساب المسافة
                $users->filter(function ($user) use ($pharmacyLat, $pharmacyLon, $order) {
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
                        $order->distance = round($distance, 2);

                        return true;
                    }

                    return false;
                });
                $urgentorders[] = $order;


            }








            return view('warehouse.urgentorders.index', compact('urgentorders'));
        } else {
            $urgentorders = false;


            return view('warehouse.urgentorders.index', compact('urgentorders'));
        }
    }

    public function show($orderId, $pharmacyId)
    {
        $pharmacy = User::find($pharmacyId);
        $order = UrgentOrder::with('items')->findOrFail($orderId);
        return view('warehouse.urgentorders.show', compact(['pharmacy', 'order']));
    }

    public function create()
    {

        return view('pharmacy.urgentorders.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);


        $order = UrgentOrder::create([
            'pharmacy_id' => Auth::id(),
            'note' => $request->note,
            'status' => 'pending',
        ]);


        foreach ($request->items as $item) {
            $order->items()->create([
                'name' => $item['name'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('pharmacy.warehouses.index')->with('success', 'تم إنشاء الطلبية بنجاح');
    }


    public function approve($orderId)
    {

        $order = UrgentOrder::find($orderId);

        $order->status = 'ready';
        $order->save();
        $pharmacy_id = $order->pharmacy_id;

        $medicines = Medicine::where('warehouse_id', Auth::user()->warehouse->id)

            ->where('quantity', '>', 0)
            ->get();

        return view('warehouse.urgentorders.create', compact('pharmacy_id', 'medicines', 'order'));
    }





    public function storeManual(Request $request)
    {

        $warehouseId = Auth::user()->warehouse->id;

        $request->validate([
            'pharmacy_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $warehouseId) {
            // 1. إنشاء الطلبية بحالة pending
            $order = Order::create([
                'pharmacy_id' => $request->pharmacy_id,
                'warehouse_id' => $warehouseId,
                'status' => 'pending',
                'total_price' => 0, // سيتم تحديثه لاحقًا
            ]);

            $totalPrice = 0;

            // 2. إضافة العناصر إلى الطلبية
            foreach ($request->items as $itemData) {
                $medicine = Medicine::where('id', $itemData['medicine_id'])
                    ->where('warehouse_id', $warehouseId)
                    ->firstOrFail();

                $subtotal = $medicine->selling_price * $itemData['quantity'];
                $totalPrice += $subtotal;
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $itemData['quantity'],
                    'price_per_unit' => $medicine->selling_price,
                    'subtotal' => $subtotal,
                ]);
            }

            // 3. تحديث إجمالي الطلبية
            $order->update([
                'total_price' => $totalPrice,
            ]);
        });

        return redirect()->route('warehouse.orders.index')->with('success', 'تم إنشاء الطلبية اليدوية بنجاح.');
    }
}
