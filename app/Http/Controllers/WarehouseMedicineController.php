<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Company;
use App\Models\Supplier;
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
            ->groupBy('name', 'company_id', 'warehouse_id', 'price', 'quantity', 'date', 'barcode', 'offer','discount_percentage', 'profit_percentage', 'selling_price', 'created_at', 'updated_at', 'id')
            ->get();

        $suppliers = Supplier::all();

        return view('warehouse.medicines.create', compact(['medicines', 'suppliers']));
    }







    public function store(Request $request)
    {
        $warehouse = auth()->user()->warehouse;

        // التحقق من صحة البيانات قبل أي عمليات
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
        ]);

        foreach ($request->items as $item) {
            // حساب سعر البيع
            $selling_price = $item['price'];
            if (!empty($item['profit_percentage'])) {
                $selling_price += ($item['profit_percentage'] / 100) * $item['price'];
            }

            // الحصول على الشركة أو إنشاؤها
            $company = Company::firstOrCreate(['name' => $item['company_name']]);

            // البحث عن الدواء في المستودع المحدد بنفس الاسم والشركة
            $medicine = Medicine::where('name', $item['name'])
                ->where('company_id', $company->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();

            if ($medicine) {
                // تحديث الدواء إذا كان موجودًا
                $medicine->update([
                    'price' => $item['price'],
                    'quantity' => $medicine->quantity + $item['quantity'],
                    'date' => $item['date'],
                    'barcode' => $item['barcode'] ?? $medicine->barcode, // الحفاظ على الباركود القديم إذا لم يُرسل جديد
                    'offer' => $item['offer'],
                    'profit_percentage' => $item['profit_percentage'],
                    'selling_price' => $selling_price,
                ]);
            } else {
                // إنشاء دواء جديد إذا لم يكن موجودًا
                Medicine::create([
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
        }

        return redirect()->route('warehouse.medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
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
