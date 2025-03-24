<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeePayment;
use App\Models\WarehouseCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::where('warehouse_id', auth()->user()->warehouse->id)
            ->with('payments')
            ->get();
        return view('warehouse.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('warehouse.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        Employee::create([
            'warehouse_id' => auth()->user()->warehouse->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'position' => $validated['position'],
            'salary' => $validated['salary'],
            'date' => $validated['date'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('warehouse.employees.index')->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function show(Employee $employee)
    {
        if ($employee->warehouse_id !== auth()->user()->warehouse->id) {
            abort(403, 'غير مصرح لك بعرض هذا الموظف');
        }
        $employee->load('payments');
        return view('warehouse.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        if ($employee->warehouse_id !== auth()->user()->warehouse->id) {
            abort(403, 'غير مصرح لك بتعديل هذا الموظف');
        }
        return view('warehouse.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->warehouse_id !== auth()->user()->warehouse->id) {
            abort(403, 'غير مصرح لك بتعديل هذا الموظف');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $employee->update($validated);

        return redirect()->route('warehouse.employees.index')->with('success', 'تم تعديل الموظف بنجاح');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->warehouse_id !== auth()->user()->warehouse->id) {
            abort(403, 'غير مصرح لك بحذف هذا الموظف');
        }
        $employee->delete();
        return redirect()->route('warehouse.employees.index')->with('success', 'تم حذف الموظف بنجاح');
    }

    public function paySalary(Request $request, Employee $employee)
    {
        if ($employee->warehouse_id !== auth()->user()->warehouse->id) {
            abort(403, 'غير مصرح لك بتسجيل دفعة لهذا الموظف');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($employee, $validated) {
            // تسجيل الدفعة
            $payment = EmployeePayment::create([
                'employee_id' => $employee->id,
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'note' => $validated['note'],
            ]);

            // تسجيل المصروف في الصندوق
            WarehouseCash::create([
                'warehouse_id' => auth()->user()->warehouse->id,
                'transaction_type' => 'expense',
                'amount' => $validated['amount'],
                'description' => 'راتب الموظف: ' . $employee->name . ' - ' . ($validated['note'] ?? 'راتب شهري'),
                'date' => $validated['date'],
                'related_id' => $payment->id,
                'related_type' => 'employee_payment',
            ]);
        });

        return redirect()->route('warehouse.employees.show', $employee->id)->with('success', 'تم تسجيل دفعة الراتب بنجاح');
    }
}
