<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{

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