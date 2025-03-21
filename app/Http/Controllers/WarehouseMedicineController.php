<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use Illuminate\Http\Request;
use Mockery\Generator\StringManipulation\Pass\Pass;

class WarehouseMedicineController extends Controller
{
    public function index()
    {
        $warehouse = auth()->user()->warehouse;
        $medicines = Medicine::where('warehouse_id', $warehouse->id)
            ->with('company')
            ->orderBy('company_id') // ترتيب حسب الشركة أولاً
            ->get()
            ->groupBy('company.name'); // تجميع حسب اسم الشركة

        $expiringMedicines = Medicine::where('warehouse_id', $warehouse->id)
            ->where(function ($query) {
                $query->where('quantity', '<=', 10)
                    ->orWhere('date', '<=', now()->addMonth());
            })
            ->with('company')
            ->get();

        return view('warehouse.medicines.index', compact('medicines', 'expiringMedicines'));
    }

    public function create()
    {
        $warehouse = auth()->user()->warehouse;

        // جلب الأدوية بدون تكرار الاسم مع الاحتفاظ بالمعلومات الأخرى
        $medicines = Medicine::where('warehouse_id', $warehouse->id)
            ->with('company')
            ->groupBy('name', 'company_id', 'warehouse_id', 'price', 'quantity', 'date', 'barcode', 'offer', 'discount_percentage', 'profit_percentage', 'selling_price', 'created_at', 'updated_at', 'id')
            ->get();

        $suppliers = Supplier::all();

        return view('warehouse.medicines.create', compact(['medicines', 'suppliers']));
    }







    public function store(Request $request)
    {
        $warehouse = auth()->user()->warehouse;

        // التحقق من صحة البيانات
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.company_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.date' => 'required|date|after:today',
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

        $supplyOrder = SupplyOrder::create([
            'supplier_id' => $request->supplier_id,
            'discount_type' => $request->discount_type,
            'total_quantity' => 0,
            'total_cost_before_discount' => 0,
            'total_cost_after_discount' => 0,
            'discount_amount' => 0,
            'order_date' => now(),
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
                'supply_order_id' => $supplyOrder->id,
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
        $supplyOrder->update([
            'total_quantity' => $total_quantity,
            'total_cost_before_discount' => $total_cost_before_discount,
            'total_cost_after_discount' => $total_cost_after_discount,
            'discount_amount' => $total_discount_amount,
        ]);

        // تحديث دين المورد في جدول suppliers
        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier->update([
            'debt' => $supplier->debt + $total_cost_after_discount, // إضافة قيمة الفاتورة إلى الدين
            'total_orders' => $supplier->total_orders + 1, // زيادة عدد الطلبيات
            'balance' => $supplier->balance + $total_cost_after_discount, // تحديث الرصيد
            'total_discounts'=>$supplier->total_discounts+$total_discount_amount
        ]);

        return redirect()->route('warehouse.medicines.index')->with('success', 'تمت إضافة الطلب بنجاح.');
    }









    public function edit(Medicine $medicine)
    {
        if ($medicine->warehouse_id !== auth()->user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $companies = Company::all();
        return view('warehouse.medicines.edit', compact('medicine', 'companies'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        if ($medicine->warehouse_id !== auth()->user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'date' => 'required|date|after:today',
            'barcode' => 'nullable|string|max:50|unique:medicines,barcode,' . $medicine->id,
            'offer' => 'nullable|string|max:255',
        ]);

        $medicine->update([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'barcode' => $request->barcode,
            'offer' => $request->offer,
        ]);

        return redirect()->route('warehouse.medicines.index')->with('success', 'تم تعديل الدواء بنجاح.');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->warehouse_id !== auth()->user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $medicine->delete();
        return back()->with('success', 'تم حذف الدواء بنجاح.');
    }
}
