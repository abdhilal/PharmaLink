<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyOrderController extends Controller
{

    public function show($id)
    {
        $supplyOrder = SupplyOrder::with(['items.medicine', 'supplier'])
            ->whereHas('supplier', function ($query) {
                $query->where('warehouse_id', auth()->user()->warehouse->id);
            })
            ->findOrFail($id);

        return view('warehouse.supply.order.show', compact('supplyOrder'));
    }

    // عرض صفحة تعديل الطلبية
    public function edit($orderId)
    {
        // جلب طلبية التوريد مع العلاقات المرتبطة (الأصناف، الأدوية، المورد)
        $order = SupplyOrder::with(['supplier', 'items.medicine.company'])->find($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'الطلبية غير موجودة');
        }

        $warehouse = auth()->user()->warehouse;

        // جلب الأدوية بدون تكرار الاسم مع الاحتفاظ بالمعلومات الأخرى
        $medicines = Medicine::where('warehouse_id', $warehouse->id)
            ->with('company')
            ->groupBy('name', 'company_id', 'warehouse_id', 'price', 'quantity', 'date', 'barcode', 'offer', 'discount_percentage', 'profit_percentage', 'selling_price', 'created_at', 'updated_at', 'id','is_hidden')
            ->get();

        $suppliers = Supplier::all();

        return view('warehouse.supply.order.edit', compact('medicines', 'suppliers', 'order'));
    }


    // تحديث الطلبية
    public function update(Request $request, $orderId)
    {
        $order = SupplyOrder::with(['supplier', 'items.medicine.company'])->find($orderId);
        $warehouse = auth()->user()->warehouse;

        // التحقق من صحة البيانات
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.company_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.date' => 'required|date',
            'items.*.barcode' => 'nullable|string|max:50',
            'items.*.offer' => 'nullable|string|max:255',
            'items.*.profit_percentage' => 'nullable|numeric',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100', // نسبة الخصم على الصنف
            'discount_type' => 'required|string|in:total,per_item', // نوع الخصم
            'discount_percentage' => 'nullable|numeric|min:0|max:100', // نسبة الخصم على الفاتورة
            'supplier_id' => 'required|exists:suppliers,id', // التأكد من أن المورد موجود
        ]);

        $total_quantity = 0;
        $total_cost_before_discount = 0;
        $total_cost_after_discount = 0;
        $total_discount_amount = 0;
        foreach ($order->items as $order_item) {
            // استعادة الكمية القديمة من الدواء
            $order_item->medicine->update([
                'quantity' => $order_item->medicine->quantity - $order_item->quantity,
            ]);

            $order_item->delete();
        }

        $order->update([
            'discount_type' => $request->discount_type,
            'note' => $request->note,
        ]);

        foreach ($request->items as $item) {
            // حساب سعر البيع
            $selling_price = $item['price'];
            if (!empty($item['profit_percentage'])) {
                $selling_price += ($item['profit_percentage'] / 100) * $item['price'];
            }

            // تحديث مجموع الكميات
            $total_quantity += $item['quantity'];

            // التكلفة الإجمالية قبل الخصم لهذا الصنف
            $item_total_cost = $item['quantity'] * $item['price'];
            $total_cost_before_discount += $item_total_cost;

            // حساب الخصم بناءً على نوع الخصم
            $discount_amount = 0;

            if ($request->discount_type == 'per_item') {
                // إذا كان الخصم على صنف معين فقط
                $discount_amount = $item_total_cost * ($item['discount_percentage'] / 100);
            }

            // التكلفة بعد الخصم لهذا الصنف
            $item_cost_after_discount = $item_total_cost - $discount_amount;
            $total_cost_after_discount += $item_cost_after_discount;
            $total_discount_amount += $discount_amount;

            // الحصول على الشركة أو إنشاؤها
            $company = Company::firstOrCreate(['name' => $item['company_name']]);

            // البحث عن الدواء
            $medicine = Medicine::where('name', $item['name'])
                ->where('company_id', $company->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();


            if ($medicine) {


                // تحديث بيانات الدواء إذا كان موجودًا
                $medicine->update([
                    'price' => $item['price'],
                    'quantity' => $medicine->quantity + $item['quantity'],
                    'date' => $item['date'],
                    'barcode' => $item['barcode'] ?? $medicine->barcode,
                    'offer' => $item['offer'],
                    'profit_percentage' => $item['profit_percentage'],
                    'selling_price' => $selling_price,
                ]);
            } else {

                // إنشاء دواء جديد إذا لم يكن موجودًا
                $medicine = Medicine::create([
                    'name' => $item['name'],
                    'company_id' => $company->id,
                    'warehouse_id' => $warehouse->id,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'date' => $item['date'],
                    'barcode' => $item['barcode'],
                    'offer' => $item['offer'],
                    'profit_percentage' => $item['profit_percentage'],
                    'selling_price' => $selling_price,
                ]);
            }

            // إضافة الصنف إلى جدول

            SupplyOrderItem::create([
                'supply_order_id' => $order->id,
                'medicine_id' => $medicine->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'discount_percentage' => $item['discount_percentage'] ?? 0,
                'discount_amount' => $discount_amount,
                'subtotal' => $item_cost_after_discount,
            ]);
        }

        // إذا كان الخصم على إجمالي الفاتورة
        if ($request->discount_type == 'total') {
            $total_discount_amount = $total_cost_before_discount * ($request->discount_percentage / 100);
            $total_cost_after_discount = $total_cost_before_discount - $total_discount_amount;
        }

        // تحديث بيانات الطلب بعد الحسابات النهائية


        // تحديث دين المورد في جدول suppliers
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier->update([
            'debt' => ($supplier->debt-$order->total_cost_after_discount) + $total_cost_after_discount, // إضافة قيمة الفاتورة إلى الدين
            'balance' => ($supplier->balance-$order->total_cost_after_discount) + $total_cost_after_discount, // تحديث الرصيد
            'total_discounts' => ($supplier->total_discounts -$order->discount_amount)+ $total_discount_amount
        ]);

        $order->update([
            'total_quantity' => $total_quantity,
            'total_cost_before_discount' => $total_cost_before_discount,
            'total_cost_after_discount' => $total_cost_after_discount,
            'discount_amount' => $total_discount_amount,
        ]);

        return redirect()->route('warehouse.suppliers.show', $supplier->id)->with('success', 'تمت تحديث الطلب بنجاح.');
    }




    public function destroy($orderId)
    {
        $supplier = null; // تعريف المورد قبل `transaction`

        DB::transaction(function () use ($orderId, &$supplier) {
            // جلب الطلبية مع الأصناف المرتبطة بها
            $order = SupplyOrder::with(['items.medicine', 'supplier'])->findOrFail($orderId);

            // حفظ المورد قبل حذف الطلبية
            $supplier = $order->supplier;

            // استرجاع كميات الأدوية إلى المستودع قبل حذف الطلبية
            foreach ($order->items as $orderItem) {
                $medicine = $orderItem->medicine;
                if ($medicine) {
                    $medicine->update([
                        'quantity' => $medicine->quantity - $orderItem->quantity, // تقليل الكمية لأن الطلبية ستُحذف
                    ]);
                }
                // حذف الصنف من الطلبية
                $orderItem->delete();
            }

            // تحديث دين المورد بعد حذف الطلبية
            if ($supplier) {
                $supplier->update([
                    'debt' => $supplier->debt - $order->total_cost_after_discount,
                    'balance' => $supplier->balance - $order->total_cost_after_discount,
                    'total_discounts' => $supplier->total_discounts - $order->discount_amount,
                ]);
            }

            // حذف الطلبية
            $order->delete();
        });

        // التأكد من أن المورد موجود قبل إعادة التوجيه
        if ($supplier) {
            return redirect()->route('warehouse.suppliers.show', $supplier->id)
                ->with('success', 'تم حذف الطلبية بنجاح.');
        } else {
            return redirect()->route('warehouse.orders.index')
                ->with('success', 'تم حذف الطلبية بنجاح.');
        }
    }


}
