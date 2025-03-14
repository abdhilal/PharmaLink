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
    public function addToCart(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        if ($medicine->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock.');
        }

        $cart = session()->get('cart', []);
        $warehouseId = $medicine->warehouse_id;

        // إضافة الدواء إلى السلة تحت المستودع الخاص به
        $cart[$warehouseId][$medicine->id] = [
            'quantity' => $request->quantity,
            'price' => $medicine->price,
            'offer' => $medicine->offer,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Medicine added to cart.');
    }

    // عرض السلة
    public function show()
    {
        $cart = session()->get('cart', []);
        $invoices = [];

        foreach ($cart as $warehouseId => $items) {
            $subtotal = 0;
            foreach ($items as $medicineId => $details) {
                $medicine = Medicine::find($medicineId);
                $quantity = $details['quantity'];
                $price = $details['price'];
                $offer = $details['offer'];

                // تطبيق العرض إذا وجد
                $itemTotal = $price * $quantity;
                if ($offer && str_contains($offer, '10% off on 10+ units') && $quantity >= 10) {
                    $itemTotal *= 0.9; // خصم 10%
                }

                $subtotal += $itemTotal;
                $invoices[$warehouseId]['items'][] = [
                    'medicine' => $medicine,
                    'quantity' => $quantity,
                    'subtotal' => $itemTotal,
                ];
            }
            $invoices[$warehouseId]['total'] = $subtotal;
        }

        return view('cart.show', compact('invoices'));
    }

    // إرسال الطلبيات
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        DB::transaction(function () use ($cart) {
            foreach ($cart as $warehouseId => $items) {
                $total = 0;
                $order = Order::create([
                    'pharmacy_id' => auth()->user()->id,
                    'warehouse_id' => $warehouseId,
                    'total_price' => 0, // سيتم تحديثه لاحقًا
                ]);

                foreach ($items as $medicineId => $details) {
                    $medicine = Medicine::find($medicineId);
                    $quantity = $details['quantity'];
                    $price = $details['price'];
                    $offer = $details['offer'];

                    $subtotal = $price * $quantity;
                    if ($offer && str_contains($offer, '10% off on 10+ units') && $quantity >= 10) {
                        $subtotal *= 0.9;
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'medicine_id' => $medicineId,
                        'quantity' => $quantity,
                        'price_per_unit' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    // تحديث الكمية في المستودع
                    $medicine->quantity -= $quantity;
                    $medicine->save();

                    $total += $subtotal;
                }

                $order->total_price = $total;
                $order->save();
            }

            session()->forget('cart');
        });

        return redirect()->route('orders.index')->with('success', 'Orders placed successfully.');
    }
}