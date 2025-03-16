<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    

  public function __invoke(Request $request)
  {
      $user = auth()->user();
      if ($user->role === 'pharmacy') {
          $accounts = Account::where('pharmacy_id', $user->id)->with('transactions')->get();
      } else {
          $accounts = Account::where('warehouse_id', $user->warehouse->id)->with('transactions')->get();
      }
      return view('payments.index', compact('accounts'));
  }



    // تسجيل دين عند تسليم الطلبية
    public function recordDebt(Order $order)
    {

      
        $this->authorizeOrder($order);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Order must be delivered to record debt.');
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


        return back()->with('success', 'Debt recorded successfully.');
    }

    // تسجيل دفع من الصيدلية إلى المستودع
    public function makePayment(Request $request)
    {

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
        ]);
        $order = Order::findOrFail($request->order_id);
        $this->authorizeOrder($order);

        $account = Account::where('pharmacy_id', $order->pharmacy_id)
                         ->where('warehouse_id', $order->warehouse_id)
                         ->firstOrFail();

        if ($request->amount > $account->balance) {
            return back()->with('error', 'Payment amount exceeds outstanding balance.');
        }

        DB::transaction(function () use ($account, $order, $request) {
            // تسجيل الدفع
            Transaction::create([
                'account_id' => $account->id,
                'order_id' => $order->id,
                'amount' => $request->amount,
                'type' => 'payment',
            ]);

            // تحديث الرصيد (الدفع يقلل الرصيد المستحق)
            $account->balance -= $request->amount;
            $account->save();
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    // التحقق من صلاحية المستخدم
    private function authorizeOrder(Order $order)
    {
        if (auth()->user()->role === 'pharmacy' && auth()->id() !== $order->pharmacy_id) {
            abort(403, 'Unauthorized action.');
        }
        if (auth()->user()->role === 'warehouse' && auth()->user()->warehouse->id !== $order->warehouse_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}