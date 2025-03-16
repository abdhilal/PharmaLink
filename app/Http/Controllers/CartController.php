<?php
namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//كتابة منطق أولي لحساب الفواتير في السلة مع تطبيق العروض
class CartController extends Controller
{
    

    // إضافة دواء إلى السلة (تخزين مؤقت في الجلسة)

   
    public function addMultiple(Request $request)
    {
      
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    
        $cart = session()->get('cart', []);
    
        foreach ($request->items as $item) {
            $medicine = Medicine::findOrFail($item['medicine_id']);
            if ($medicine->quantity < $item['quantity']) {
                return back()->with('error', "Insufficient stock for {$medicine->name}.");
            }
    
            $subtotal = $medicine->price * $item['quantity'];
            if ($medicine->offer && str_contains($medicine->offer, '10% off on 10+ units') && $item['quantity'] >= 10) {
                $subtotal *= 0.9;
            }
    
            $cart[$request->warehouse_id][$medicine->id] = [
                'medicine_id' => $medicine->id,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }
    
        session()->put('cart', $cart);
        return redirect()->route('cart.show')->with('success', 'Medicines added to cart.');
    }
    // عرض السلة
    public function show()
    {
        $cart = session()->get('cart', []);
        $invoices = [];
        
        foreach ($cart as $warehouseId => $items) {
          
            $total = 0;
            $invoiceItems = [];
    
            foreach ($items as $item) {
              
                $medicine = Medicine::find($item['medicine_id']);
                $subtotal = $item['subtotal'];
                // استخدام price_per_unit إذا كان موجودًا، وإلا استخدام السعر من Medicine
                $pricePerUnit = $item['price_per_unit'] ?? $medicine->price;
    
                $invoiceItems[] = [
                    'medicine' => $medicine,
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }

    
            $invoices[$warehouseId] = [
                'items' => $invoiceItems,
                'total' => $total,
            ];
        }
    
        return view('cart.show', compact('invoices'));
    }

    // إرسال الطلبيات
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }
    
        foreach ($cart as $warehouseId => $items) {
            $order = Order::create([
                'pharmacy_id' => auth()->user()->id,
                'warehouse_id' => $warehouseId,
                'status' => 'pending',
                'total_price' => array_sum(array_column($items, 'subtotal')),
            ]);
    
            foreach ($items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']); // جلب الدواء
                $pricePerUnit = $item['price_per_unit'] ?? $medicine->price; // استخدام السعر من Medicine إذا لم يكن موجودًا
    
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $item['subtotal'],
                ]);
            }
    
           
        }
    
        session()->forget('cart');
        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }


    public function update(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $cart = session()->get('cart', []);
        $medicine = Medicine::findOrFail($request->medicine_id);
    
        // التحقق من المخزون
        if ($medicine->quantity < $request->quantity) {
            return back()->with('error', "Insufficient stock for {$medicine->name}.");
        }
    
        // جلب العنصر من السلة
        if (!isset($cart[$request->warehouse_id][$request->medicine_id])) {
            return back()->with('error', 'Item not found in cart.');
        }
    
        // حساب السعر الجديد
        $pricePerUnit = $cart[$request->warehouse_id][$request->medicine_id]['price_per_unit'] ?? $medicine->price;
        $subtotal = $pricePerUnit * $request->quantity;
    
        // تطبيق العروض إذا وجدت
        if ($medicine->offer && str_contains($medicine->offer, '10% off on 10+ units') && $request->quantity >= 10) {
            $subtotal *= 0.9;
        }
    
        // تحديث العنصر في السلة
        $cart[$request->warehouse_id][$request->medicine_id] = [
            'medicine_id' => $request->medicine_id,
            'quantity' => $request->quantity,
            'price_per_unit' => $pricePerUnit,
            'subtotal' => $subtotal,
        ];
    
        session()->put('cart', $cart);
        return back()->with('success', 'Cart updated successfully.');
    }

public function remove(Request $request)
{
    $request->validate([
        'medicine_id' => 'required|exists:medicines,id',
        'warehouse_id' => 'required|exists:warehouses,id',
    ]);

    $cart = session()->get('cart', []);
    unset($cart[$request->warehouse_id][$request->medicine_id]);
    if (empty($cart[$request->warehouse_id])) {
        unset($cart[$request->warehouse_id]);
    }
    session()->put('cart', $cart);
    return back()->with('success', 'Item removed from cart.');
}

}