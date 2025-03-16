<?php
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Medicine;
use App\Models\Company;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseMedicineController extends Controller
{
 

    // دالة لعرض قائمة الأدوية الخاصة بالمستودع
    public function index()
    {
        $warehouse = auth()->user()->warehouse; // جلب المستودع المرتبط بالمستخدم الحالي
        $medicines = $warehouse->medicines()->with('company')->get(); // جلب الأدوية مع بيانات الشركة
        return view('warehouse.medicines.index', compact('medicines', 'warehouse')); // عرض الواجهة مع البيانات
    }

    // دالة لعرض صفحة إضافة دواء جديد
    public function create()
    {
        $companies = Company::all(); // جلب جميع الشركات لاختيار واحدة
        $cities = City::all(); // جلب جميع المدن لتحديد المدن التي يخدمها المستودع
        return view('warehouse.medicines.create', compact('companies', 'cities')); // عرض صفحة الإضافة
    }

    // دالة لتخزين دواء جديد في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => 'required|string|max:255', // اسم الدواء مطلوب ولا يزيد عن 255 حرف
            'company_id' => 'required|exists:companies,id', // معرف الشركة مطلوب ويجب أن يكون موجودًا
            'price' => 'required|numeric|min:0', // السعر مطلوب ويجب أن يكون رقمًا موجبًا
            'quantity' => 'required|integer|min:0', // الكمية مطلوبة ويجب أن تكون عددًا صحيحًا موجبًا
            'offer' => 'nullable|string|max:255', // العرض اختياري ولا يزيد عن 255 حرف
            'cities' => 'required|array', // قائمة المدن مطلوبة ويجب أن تكون مصفوفة
            'cities.*' => 'exists:cities,id', // كل مدينة يجب أن تكون موجودة في جدول المدن
        ]);

        $warehouse = auth()->user()->warehouse; // جلب المستودع المرتبط بالمستخدم الحالي

        // إنشاء دواء جديد في جدول medicines
        $medicine = Medicine::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'warehouse_id' => $warehouse->id, // ربط الدواء بالمستودع
            'price' => $request->price,
            'quantity' => $request->quantity,
            'offer' => $request->offer,
        ]);

        // تحديث المدن التي يخدمها المستودع في جدول warehouse_cities
        $warehouse->cities()->sync($request->cities); // استخدام sync لتحديث المدن دون تكرار

        return redirect()->route('warehouse.medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
    }
}