<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WarehouseCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

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



    public function storePayment(Request $request, $pharmacyId)
    {
        $pharmacy = User::where('role', 'pharmacy')->findOrFail($pharmacyId);
        $account = Account::where('warehouse_id', auth()->user()->warehouse->id)
            ->where('pharmacy_id', $pharmacy->id)
            ->firstOrFail();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $account->balance,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card',
            'note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($account, $validated) {
            Transaction::create([
                'account_id' => $account->id,
                'amount' => $validated['amount'],
                'type' => 'payment',
                'created_at' => $validated['payment_date'],
            ]);

            $account->balance -= $validated['amount'];
            $account->save();

            // تسجيل الدخل في الصندوق
            WarehouseCash::create([
                'warehouse_id' => auth()->user()->warehouse->id,
                'transaction_type' => 'income',
                'amount' => $validated['amount'],
                'description' => "دفعة من الصيدلية: " . $account->pharmacy->name . " - " . ($validated['note'] ?? 'دفعة نقدية'),
                'date' => $validated['payment_date'],
            ]);
        });

        return redirect()->route('warehouse.pharmacies.show', $pharmacy->id)
            ->with('success', 'تم تسجيل الدفعة بنجاح.');
    }


    public function createPayment($pharmacyId)
    {
        $pharmacy = User::where('role', 'pharmacy')->findOrFail($pharmacyId);
        $account = Account::where('warehouse_id', auth()->user()->warehouse->id)
            ->where('pharmacy_id', $pharmacy->id)
            ->firstOrFail();
        return view('warehouse.payments.create', compact('pharmacy', 'account'));
    }


    //مشان الصيدلي يشوف دينو
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
