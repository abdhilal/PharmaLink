<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers=Supplier::where('warehouse_id',Auth::user()->warehouse->id)->get();

        return view('warehouse.supply.index',compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouse.supply.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier = Supplier::create([
            'warehouse_id' => auth()->user()->warehouse->id, // افتراض أن المستخدم مرتبط بمستودع
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'total_orders' => 0, // القيم الافتراضية
            'debt' => 0.00,
            'total_paid' => 0.00,
            'total_discounts' => 0.00,
            'balance' => 0.00,
        ]);

        return redirect()->route('warehouse.suppliers.index')
            ->with('success', 'تم إضافة المورد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($supplier)
    {
        $warehouse = auth()->user()->warehouse;
        $supplier = Supplier::where('warehouse_id', $warehouse->id)->where('id', $supplier)->first();

     return view('warehouse.supply.show',compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($supplier)
    {
        $warehouse = auth()->user()->warehouse;
        $supplier = Supplier::where('warehouse_id', $warehouse->id)->where('id', $supplier)->first();

     return view('warehouse.supply.edit',compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $warehouse = auth()->user()->warehouse;

        $supplier = Supplier::where('warehouse_id',$warehouse->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);


        $supplier->update($validated);

        return redirect()->route('warehouse.suppliers.index')
            ->with('success', 'تم تعديل بيانات المورد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
