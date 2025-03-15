<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{

  public function index(Request $request)
  {
      $warehouseId = $request->query('warehouse');
      if (!$warehouseId) {
          return redirect()->route('warehouses.index')->with('error', 'Please select a warehouse first.');
      }

      $medicines = Medicine::where('warehouse_id', $warehouseId)
          ->with('company', 'warehouse')
          ->orderBy('company_id')
          ->orderBy('price')
          ->get()
          ->groupBy('company.name');

      return view('medicines.index', compact('medicines', 'warehouseId'));
}

  public function store(Request $request)
  {
      $request->validate([
          'company_id' => 'required|exists:companies,id',
          'name' => 'required|string|max:255',
          'price' => 'required|numeric|min:0',
          'quantity' => 'required|integer|min:0',
          'offer' => 'nullable|string|max:255',
      ]);

      Medicine::create([
          'warehouse_id' => Auth()->user()->warehouse->id,
          'company_id' => $request->company_id,
          'name' => $request->name,
          'price' => $request->price,
          'quantity' => $request->quantity,
          'offer' => $request->offer,
      ]);
      return redirect()->back()->with('success', 'Medicine added successfully.');
}
}