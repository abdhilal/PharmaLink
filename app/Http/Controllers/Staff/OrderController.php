<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $staff = auth()->user();
        $warehouseId = $staff->parent_id; // المستودع الذي يتبع له الموظف

        $orders = Order::where('warehouse_id', $warehouseId)
            ->where('status', 'ready') // افترضنا حالة "ready"
            ->with('pharmacy.city')
            ->get();

        return view('warehouse.staff.orders.index', compact('orders'));
    }

    public function deliver(Order $order)
    {
        if (!auth()->user()->can('deliver-orders')) {
            abort(403, 'غير مصرح لك بتسليم الطلبيات');
        }

        $order->update(['status' => 'delivered']);
        return redirect()->route('staff.orders.index')->with('success', 'تم تحويل الطلبية إلى مسلمة!');
    }
}
