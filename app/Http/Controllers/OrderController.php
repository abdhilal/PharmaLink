<?php
namespace App\Http\Controllers;

use App\Models\Order;
use DB;
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
    // التحقق من صحة البيانات المدخلة
    $request->validate([
      'status' => 'required|in:pending,ready,delivered', // الحالات المسموح بها
    ]);

    // التأكد من أن المستخدم هو مستودع وله صلاحية على الطلبية
    if (auth()->user()->role !== 'warehouse' || !auth()->user()->warehouse || $order->warehouse_id !== auth()->user()->warehouse->id) {
      return back()->with('error', 'عملية غير مصرح بها.');
    }

    // تحميل العناصر المرتبطة بالطلبية فقط عند الحاجة
    if (!$order->relationLoaded('items.medicine')) {
      $order->load('items.medicine');
    }
    // استخدام معاملة لضمان الاتساق في قاعدة البيانات
    DB::transaction(function () use ($request, $order) {

      // إذا أصبحت الحالة "delivered" ولم تكن كذلك من قبل، نقص الكمية وسجل الدين
      if ($request->status === 'delivered' && $order->status !== 'delivered') {

        foreach ($order->items as $item) { // حلقة على عناصر الطلبية
          $medicine = $item->medicine; // جلب الدواء المرتبط
          if (!$medicine) { // التحقق من وجود الدواء
            return back()->with('error', "الدواء غير موجود للعنصر #{$item->id}.");
          }
          if ($medicine->quantity < $item->quantity) { // التحقق من الكمية المتاحة
            return back()->with('error', "الكمية غير كافية لـ {$medicine->name}.");
          }
          $medicine->quantity -= $item->quantity; // تقليص الكمية المتاحة
          $medicine->save(); // حفظ التغييرات في جدول medicines
        }

        // تسجيل الدين بعد خصم الكمية
        // تحديث حالة الطلبية
        $order->update(['status' => $request->status]);


        resolve(PaymentController::class)->recordDebt($order);
      }

    });



    return back()->with('success', 'تم تحديث حالة الطلبية بنجاح.');
  }





}