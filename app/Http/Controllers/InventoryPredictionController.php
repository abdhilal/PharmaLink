<?php

namespace App\Http\Controllers;

use App\Models\InventoryPrediction;
use App\Models\Medicine;
use Illuminate\Http\Request;

class InventoryPredictionController extends Controller
{
    public function index()
    {
        $predictions = InventoryPrediction::with('medicine')
            ->whereDate('prediction_date', '>=', now()->subDays(30))
            ->orderBy('prediction_date', 'desc')
            ->paginate(10);

        return view('inventory-prediction.index', compact('predictions'));
    }

    public function generate()
    {
        $medicines = Medicine::all();
        
        foreach ($medicines as $medicine) {
            InventoryPrediction::generatePrediction($medicine->id);
        }

        return redirect()->back()->with('success', 'Predictions generated successfully');
    }

    public function show(Medicine $medicine)
    {
        $prediction = InventoryPrediction::where('medicine_id', $medicine->id)
            ->latest()
            ->firstOrFail();

        return view('inventory-prediction.show', compact('prediction', 'medicine'));
    }
}
