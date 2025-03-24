<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    //  * عرض جميع الطلبيات حسب دور المستخدم.




    public function index(Request $request)
    {
        $warehouseId = Auth::user()->warehouse->id;
        $query = Order::where('warehouse_id', $warehouseId)->with('items.medicine', 'pharmacy');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // بحث حسب اسم الصيدلية
        if ($request->filled('search')) {
            $query->whereHas('pharmacy', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(10);
        return view('warehouse.orders.index', compact('orders'));
    }


    /**
     * عرض تفاصيل الطلبية.
     */
    public function show(Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $order->load('items.medicine', 'pharmacy');
        return view('warehouse.orders.show', compact('order'));
    }


    /**
     * الموافقة على الطلبية وتسليمها.
     */
    public function approve(Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'pending') {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        DB::transaction(function () use ($order) {
            $order->load('items.medicine');
            foreach ($order->items as $item) {
                $medicine = $item->medicine;
                if ($medicine->quantity < $item->quantity) {
                    throw new \Exception("الكمية غير كافية لـ {$medicine->name}.");
                }
                $medicine->quantity -= $item->quantity;
                $medicine->save();
            }
            $order->status = 'delivered';
            $order->save();
            app(PaymentController::class)->recordDebt($order);
        });

        return back()->with('success', 'تمت الموافقة على الطلبية بنجاح.');
    }


    /**
     * عرض صفحة تعديل الطلبية.
     */

    public function edit($orderId)
    {
        $order = Order::with('items.medicine')->findOrFail($orderId);


        // if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'pending') {
        //     return back()->with('error', 'عملية غير مصرح بها أو الطلبية غير قابلة للتعديل.');
        // }

        $medicines = Medicine::where('warehouse_id', Auth::user()->warehouse->id)
            ->with('company')
            ->groupBy('name', 'company_id', 'warehouse_id', 'price', 'quantity', 'date', 'barcode', 'offer', 'discount_percentage', 'profit_percentage', 'selling_price', 'created_at', 'updated_at', 'id')
            ->get();

        return view('warehouse.orders.edit', compact('order', 'medicines'));
    }
    /**
     * تحديث بيانات الطلبية.
     */




    /**
     * تحديث الطلبية باستخدام طريقة المقارنة.
     */

    public function update(Request $request, Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $order) {
            $warehouseId = Auth::user()->warehouse->id;
            $totalPrice = 0;
            $wasDelivered = $order->status === 'delivered'; // حالة التسليم قبل التعديل

            // 1. إذا كانت مسلمة، استرجاع الكميات والدين القديم
            if ($wasDelivered) {
                foreach ($order->items as $item) {
                    $medicine = $item->medicine;
                    $medicine->quantity += $item->quantity; // استرجاع الكمية
                    $medicine->save();
                }
                // حذف الدين القديم
                $account = Account::where('pharmacy_id', $order->pharmacy_id)
                    ->where('warehouse_id', $warehouseId)
                    ->firstOrFail();
                $debtTransaction = Transaction::where('order_id', $order->id)
                    ->where('type', 'debt')
                    ->first();
                if ($debtTransaction) {
                    $account->balance -= $debtTransaction->amount;
                    $account->save();
                    $debtTransaction->delete();
                }
            }

            // 2. حذف العناصر القديمة
            $order->items()->delete();

            // 3. إنشاء العناصر الجديدة
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

                // إذا كانت ready أو delivered، خصم الكمية من المخزون
                if ($order->status !== 'pending') {
                    if ($medicine->quantity < $itemData['quantity']) {
                        throw new \Exception("الكمية غير كافية لـ {$medicine->name}.");
                    }
                    $medicine->quantity -= $itemData['quantity'];
                    $medicine->save();
                }
            }

            // 4. تحديث إجمالي الطلبية
            $order->total_price = $totalPrice;
            $order->save();

            // 5. إذا كانت مسلمة، إعادة تسجيل الدين
            if ($wasDelivered) {
                $account = Account::where('pharmacy_id', $order->pharmacy_id)
                    ->where('warehouse_id', $warehouseId)
                    ->firstOrFail();
                $account->balance += $totalPrice;
                $account->save();

                Transaction::create([
                    'account_id' => $account->id,
                    'order_id' => $order->id,
                    'type' => 'debt',
                    'amount' => $totalPrice,
                    'description' => "دين من طلبية مسلمة #{$order->id} بعد التعديل",
                    'date' => now(),
                ]);
            }
        });

        return redirect()->route('warehouse.orders.index')->with('success', 'تم تعديل الطلبية بنجاح.');
    }
    // public function update(Request $request, Order $order)
    // {
    //     if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'pending') {
    //         return back()->with('error', 'عملية غير مصرح بها أو الطلبية غير قابلة للتعديل.');
    //     }

    //     $request->validate([
    //         'items' => 'required|array', // يجب أن يحتوي على عنصر واحد على الأقل
    //         'items.*.medicine_id' => 'required|exists:medicines,id',
    //         'items.*.quantity' => 'required|integer|min:1',
    //     ]);

    //     DB::transaction(function () use ($request, $order) {
    //         $warehouseId = Auth::user()->warehouse->id;
    //         $totalPrice = 0;

    //         foreach ($order->items as $orderItem) {
    //             $orderItem->delete();
    //         }

    //         // 2. إنشاء العناصر الجديدة بناءً على البيانات المُرسلة
    //         foreach ($request->items as $itemData) {
    //             $medicine = Medicine::where('id', $itemData['medicine_id'])
    //                                ->where('warehouse_id', $warehouseId)
    //                                ->firstOrFail();

    //             // حساب المجموع الفرعي
    //             $subtotal = $medicine->selling_price * $itemData['quantity'];
    //             $totalPrice += $subtotal;

    //             // إنشاء العنصر في order_items
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'medicine_id' => $medicine->id,
    //                 'quantity' => $itemData['quantity'],
    //                 'price_per_unit' => $medicine->selling_price,
    //                 'subtotal' => $subtotal,
    //             ]);
    //         }

    //         // 3. تحديث إجمالي الطلبية
    //         $order->update([
    //             'total_price' => $totalPrice,
    //         ]);
    //     });

    //     return redirect()->route('warehouse.orders.index')->with('success', 'تم تعديل الطلبية بنجاح.');
    // }


    /**
     * حذف الطلبية.
     */

    public function destroy(Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'pending') {
            return back()->with('error', 'عملية غير مصرح بها أو الطلبية غير قابلة للحذف.');
        }

        $order->delete();
        return back()->with('success', 'تم حذف الطلبية بنجاح.');
    }


    /**
     * إلغاء الطلبية بعد الموافقة عليها.
     */
    public function cancel(Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'delivered') {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        DB::transaction(function () use ($order) {
            $order->load('items.medicine');
            foreach ($order->items as $item) {
                $medicine = $item->medicine;
                $medicine->quantity += $item->quantity;
                $medicine->save();
            }

            $account = Account::where('pharmacy_id', $order->pharmacy_id)
                ->where('warehouse_id', $order->warehouse_id)
                ->firstOrFail();
            $debtTransaction = Transaction::where('order_id', $order->id)
                ->where('type', 'debt')
                ->first();
            if ($debtTransaction) {
                $account->balance -= $debtTransaction->amount;
                $account->save();
                $debtTransaction->delete();
            }

            $order->status = 'cancelled';
            $order->save();
        });

        return back()->with('success', 'تم إلغاء الطلبية بنجاح.');
    }


    public function createManual()
    {

        $warehouseId = Auth::user()->warehouse->id;

        // جلب الصيدليات المرتبطة بالمستودع (التي لها حسابات مالية معه)
        $pharmacies = User::where('role', 'pharmacy')
            ->whereHas('accounts', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->get();

        // جلب الأدوية المتاحة في المستودع
        $medicines = Medicine::where('warehouse_id', $warehouseId)
            ->where('quantity', '>', 0)
            ->get();

        return view('warehouse.orders.create_manual', compact('pharmacies', 'medicines'));
    }

    /**
     * تخزين طلبية يدوية للصيدليات.
     */
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
