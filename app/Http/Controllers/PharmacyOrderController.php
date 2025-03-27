<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyOrderController extends Controller
{
    public function index(Request $request)
{
    $pharmacyId = Auth::id(); // طريقة مختصرة لجلب ID المستخدم المصادق عليه
    $query = Order::where('pharmacy_id', $pharmacyId)->with('items.medicine');

    // فلترة حسب الحالة
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $orders = $query->latest()->paginate(10);



    return view('pharmacy.orders.index', compact('orders'));
}

public function show(Order $order)
{


    $order->load('items.medicine', 'pharmacy');
    return view('pharmacy.orders.show', compact('order'));
}


public function destroy(Order $order)
{


    $order->delete();
    return back()->with('success', 'تم حذف الطلبية بنجاح.');
}



}
