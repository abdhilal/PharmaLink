<?php

namespace App\Http\Controllers;

use App\Models\MedicineExpiry;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MedicineExpiryController extends Controller
{
    public function index()
    {
        $expiringMedicines = MedicineExpiry::with('medicine')
            ->whereDate('expiry_date', '<=', Carbon::now()->addDays(30))
            ->whereDate('expiry_date', '>=', Carbon::now())
            ->orderBy('expiry_date')
            ->paginate(10);

        return view('medicine-expiry.index', compact('expiringMedicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'expiry_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:1',
            'batch_number' => 'required|string'
        ]);

        MedicineExpiry::create($request->all());

        return redirect()->back()->with('success', 'Expiry information added successfully');
    }

    public function expired()
    {
        $expiredMedicines = MedicineExpiry::with('medicine')
            ->whereDate('expiry_date', '<', Carbon::now())
            ->orderBy('expiry_date', 'desc')
            ->paginate(10);

        return view('medicine-expiry.expired', compact('expiredMedicines'));
    }
}
