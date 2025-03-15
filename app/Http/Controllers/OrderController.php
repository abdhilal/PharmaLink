<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'pharmacy') {
            $orders = Order::where('pharmacy_id', $user->id)->with('items.medicine')->get();
        } else {
            $orders = Order::where('warehouse_id', $user->warehouse->id)->with('items.medicine')->get();
        }
        return view('orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'warehouse' || auth()->user()->warehouse->id !== $order->warehouse_id) {
            abort(403);
        }
        $request->validate(['status' => 'required|in:pending,ready,delivered']);
        $order->status = $request->status;
        $order->save();


        // تسجيل الدين إذا أصبحت الحالة "delivered"
    if ($request->status === 'delivered') {
      $paymentController = app(PaymentController::class);
      $paymentController->recordDebt($order);
  }
        return back()->with('success', 'Order status updated.');
    }
}