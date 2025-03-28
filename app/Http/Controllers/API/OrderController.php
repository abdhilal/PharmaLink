<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // عرض الطلبيات الجاهزة فقط
    public function getReadyOrders(Request $request)
    {
        return  $request;
        $user = $request->user();




        $orders = Order::where('warehouse_id', $user->id)
            ->where('status', 'ready')
            ->get();

        return response()->json([
            'message' => 'الطلبيات الجاهزة',
            'orders' => $orders,
        ], 200);
    }

    public function deliverOrder(Request $request, Order $order)
    {
        $user = $request->user();



        if ($order->warehouse_id !== $user->id || $order->status !== 'ready') {
            return response()->json([
                'message' => 'لا يمكن تسليم هذا الطلب',
            ], 400);
        }

        $order->update(['status' => 'delivered']);

        return response()->json([
            'message' => 'تم تسليم الطلب بنجاح',
            'order' => $order,
        ], 200);
    }
}
