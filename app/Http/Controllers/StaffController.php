<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    // عرض قائمة الموظفين
    public function index()
    {
        $warehouse = auth()->user();
        $staff = User::where('parent_id', $warehouse->id)->get();
        return view('warehouse.staff.index', compact('staff'));
    }

    // عرض نموذج إضافة موظف
    public function create()
    {
        return view('warehouse.staff.create');
    }

    // حفظ موظف جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $warehouse = auth()->user();

        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'parent_id' => $warehouse->id,
        ]);

        $staff->assignRole('warehouse_staff');

        return redirect()->route('warehouse.staff.index')->with('success', 'تم إنشاء الموظف بنجاح!');
    }
public function edit(User $staff)
{
    $warehouse = auth()->user();
    if ($staff->parent_id !== $warehouse->id) {
        abort(403, 'غير مصرح لك بتعديل هذا الموظف');
    }

    $allowedPermissions = ['view-orders', 'deliver-orders'];
    $permissions = Permission::whereIn('name', $allowedPermissions)->get();

    return view('warehouse.staff.edit', compact('staff', 'permissions'));
}

public function update(Request $request, User $staff)
{

    $warehouse = auth()->user();
    if ($staff->parent_id !== $warehouse->id) {
        abort(403, 'غير مصرح لك بتعديل هذا الموظف');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $staff->id,
        'password' => 'nullable|string|min:8|confirmed',
        'permissions' => 'nullable|array',
        'permissions.*' => 'in:view-orders,deliver-orders', // تحقق من الأذونات المسموح بها فقط
    ]);

    $staff->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password ? Hash::make($request->password) : $staff->password,
    ]);

    // تحديث الأذونات
    $permissions = $request->permissions ?? [];
    $staff->syncPermissions($permissions);

    return redirect()->route('warehouse.staff.index')->with('success', 'تم تحديث الموظف والأذونات بنجاح!');
}
    // حذف موظف
    public function destroy(User $staff)
    {
        $warehouse = auth()->user();
        if ($staff->parent_id !== $warehouse->id) {
            abort(403, 'غير مصرح لك بحذف هذا الموظف');
        }

        $staff->delete();
        return redirect()->route('warehouse.staff.index')->with('success', 'تم حذف الموظف بنجاح!');
    }
}
