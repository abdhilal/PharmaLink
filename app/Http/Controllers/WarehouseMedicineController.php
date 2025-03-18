<?php
namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Company;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class WarehouseMedicineController extends Controller
{


    // عرض قائمة الأدوية
    public function index()
    {
        $warehouse = auth()->user()->warehouse;
        $medicines = Medicine::where('warehouse_id', $warehouse->id)->with('company')->get();
        return view('warehouse.medicines.index', compact('medicines'));
    }

    // عرض صفحة إضافة دواء
    public function create()
    {
        $companies = Company::all();
        return view('warehouse.medicines.create', compact('companies'));
    }

    // حفظ دواء جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'offer' => 'nullable|string|max:255',
        ]);

        $warehouse = auth()->user()->warehouse;

        Medicine::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'warehouse_id' => $warehouse->id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'offer' => $request->offer,
        ]);

        return redirect()->route('warehouse.medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
    }

    // عرض صفحة تعديل دواء
    public function edit(Medicine $medicine)
    {


        $companies = Company::all();
        return view('warehouse.medicines.edit', compact('medicine', 'companies'));
    }

    // تحديث بيانات الدواء
    public function update(Request $request, Medicine $medicine)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'offer' => 'nullable|string|max:255',
        ]);

        $medicine->update([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'offer' => $request->offer,
        ]);

        return redirect()->route('warehouse.medicines.index')->with('success', 'تم تعديل الدواء بنجاح.');
    }

    // حذف الدواء
    public function destroy(Medicine $medicine)
    {
        if ($medicine->warehouse_id !== auth()->user()->warehouse->id) {
            return back()->with('error', 'عملية غير مصرح بها.');
        }

        $medicine->delete();
        return back()->with('success', 'تم حذف الدواء بنجاح.');
    }
}
