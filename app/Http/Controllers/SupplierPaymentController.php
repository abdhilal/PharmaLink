<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $supplierId)
    {
        $supplier = Supplier::where('warehouse_id', auth()->user()->warehouse->id)
            ->findOrFail($supplierId);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ]);

        // تسجيل الدفعة
        SupplierPayment::create([
            'supplier_id' => $supplier->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'note' => $validated['note'],
        ]);

        // تحديث الحالة المالية للمورد
        $supplier->update([
            'total_paid' => $supplier->total_paid + $validated['amount'],
            'balance' => $supplier->balance - $validated['amount'], // تقليل الرصيد بمقدار الدفعة
        ]);

        return redirect()->route('warehouse.suppliers.show', $supplier->id)
            ->with('success', 'تم تسجيل الدفعة بنجاح');
    }
    /**
     * Display the specified resource.
     */
    public function show(SupplierPayment $supplierPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierPayment $supplierPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierPayment $supplierPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierPayment $supplierPayment)
    {
        //
    }
}
