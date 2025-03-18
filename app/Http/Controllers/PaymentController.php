<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{


    // عرض صفحة المدفوعات (للمستودع فقط)
    public function index()
    {
        $warehouse = Auth::user()->warehouse;
        $accounts = Account::where('warehouse_id', $warehouse->id)->with('transactions', 'pharmacy')->get();
        return view('warehouse.payments.index', compact('accounts'));
    }

    // تسجيل دين عند تسليم الطلبية
    public function recordDebt(Order $order)
    {
        if (Auth::user()->role !== 'warehouse' || Auth::user()->warehouse->id !== $order->warehouse_id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'الطلبية يجب أن تكون مسلمة لتسجيل الدين.');
        }

        DB::transaction(function () use ($order) {
            $account = Account::firstOrCreate([
                'pharmacy_id' => $order->pharmacy_id,
                'warehouse_id' => $order->warehouse_id,
            ]);

            // تسجيل الدين
            Transaction::create([
                'account_id' => $account->id,
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'type' => 'debt',
            ]);

            // تحديث الرصيد (الدين يزيد الرصيد المستحق)
            $account->balance += $order->total_price;
            $account->save();
        });

        return back()->with('success', 'تم تسجيل الدين بنجاح.');
    }

    // تسجيل دفع يدوي من الصيدلية إلى المستودع (للمستودع فقط)
    public function makePayment(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $account = Account::findOrFail($request->account_id);
        if (Auth::user()->warehouse->id !== $account->warehouse_id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        DB::transaction(function () use ($account, $request) {
            // تسجيل الدفع
            Transaction::create([
                'account_id' => $account->id,
                'amount' => $request->amount,
                'type' => 'payment',
            ]);

            // تحديث الرصيد (الدفع يقلل الرصيد المستحق، وقد يصبح سالبًا)
            $account->balance -= $request->amount;
            $account->save();
        });

        return back()->with('success', 'تم تسجيل الدفعة بنجاح.');
    }

    public function pharmacyBalance()
    {
        if (Auth::user()->role !== 'pharmacy') {
            abort(403, 'فقط الصيدليات يمكنها الوصول.');
        }

        $pharmacyId = Auth::user()->id;
        $accounts = Account::where('pharmacy_id', $pharmacyId)->with('transactions', 'warehouse')->get();
        return view('pharmacy.payments.index', compact('accounts'));
    }
}
