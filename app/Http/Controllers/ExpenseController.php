<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarehouseExpense;
use App\Models\WarehouseCash;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = WarehouseExpense::where('warehouse_id', auth()->user()->warehouse->id)->get();
        $totalExpenses = $expenses->sum('amount');
        return view('warehouse.expenses.index', compact('expenses', 'totalExpenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        $expense = WarehouseExpense::create([
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'note' => $validated['note'] ?? null, // في حالة كانت `note` غير موجودة
            'warehouse_id' => auth()->user()->warehouse->id,
        ]);


        WarehouseCash::create([
            'warehouse_id' => auth()->user()->warehouse->id,
            'transaction_type' => 'expense',
            'amount' => $validated['amount'],
            'description' => $validated['note'],
            'date' => $validated['date'],
            'related_id' => $expense->id,
            'related_type' => 'expense',
        ]);

        return redirect()->back()->with('success', 'تم تسجيل المصروف بنجاح');
    }
}
