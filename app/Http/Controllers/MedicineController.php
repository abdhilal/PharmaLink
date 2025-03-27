<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{

//   public function index(Request $request)
//   {
//       $warehouseId = $request->query('warehouse');
//       if (!$warehouseId) {
//           return redirect()->route('warehouses.index')->with('error', 'Please select a warehouse first.');
//       }

//       $medicines = Medicine::where('warehouse_id', $warehouseId)
//           ->with('company', 'warehouse')
//           ->orderBy('company_id')
//           ->orderBy('selling_price')
//           ->get()
//           ->groupBy('company.name');

//       return view('medicines.index', compact('medicines', 'warehouseId'));
// }


}
