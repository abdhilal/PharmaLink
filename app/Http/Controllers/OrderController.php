<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض جميع الطلبيات حسب دور المستخدم.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'pharmacy') {
            $orders = Order::where('pharmacy_id', $user->id)->with('items.medicine')->get();
        } else {
            $orders = Order::where('warehouse_id', $user->warehouse->id)->with('items.medicine')->get();
        }
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
     * تحديث حالة الطلبية (معلق، جاهز، مسلَّم).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,ready,delivered',
        ]);

        if (Auth::user()->role !== 'warehouse' || !Auth::user()->warehouse || $order->warehouse_id !== auth()->user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        DB::transaction(function () use ($request, $order) {
            if ($request->status === 'delivered' && $order->status !== 'delivered') {
                foreach ($order->items as $item) {
                    $medicine = $item->medicine;
                    if ($medicine->quantity < $item->quantity) {
                        return back()->with('error', "الكمية غير كافية لـ {$medicine->name}.");
                    }
                    $medicine->quantity -= $item->quantity;
                    $medicine->save();
                }
                $order->update(['status' => $request->status]);
                resolve(PaymentController::class)->recordDebt($order);
            }
        });

        return back()->with('success', 'تم تحديث حالة الطلبية بنجاح.');
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
    public function edit(Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'pending') {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $order->load('items.medicine');
        return view('warehouse.orders.edit', compact('order'));
    }

    /**
     * تحديث بيانات الطلبية.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $order) {
            foreach ($request->items as $itemData) {
                $item = $order->items->find($itemData['id']);
                if ($item) {
                    $item->quantity = $itemData['quantity'];
                    $item->subtotal = $item->price_per_unit * $item->quantity;
                    $item->save();
                }
            }
            $order->total_price = $order->items->sum('subtotal');
            $order->save();
        });

        return redirect()->route('orders.index')->with('success', 'تم تعديل الطلبية بنجاح.');
    }

    /**
     * حذف الطلبية.
     */
    public function destroy(Order $order)
    {
        if ($order->warehouse_id !== Auth::user()->warehouse->id || $order->status !== 'pending') {
            return back()->with('error', 'عملية غير مصرح بها.');
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
}
