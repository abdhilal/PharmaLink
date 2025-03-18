<?php
namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    

    public function cities()
    {
        $warehouse = auth()->user()->warehouse;
        $cities = City::all();
        $selectedCities = $warehouse->cities->pluck('id')->toArray();
        return view('warehouse.settings.cities', compact('cities', 'selectedCities'));
    }

    public function updateCities(Request $request)
    {
        $request->validate([
            'cities' => 'required|array',
            'cities.*' => 'exists:cities,id',
        ]);

        $warehouse = auth()->user()->warehouse;
        $warehouse->cities()->sync($request->cities);

        return redirect()->route('warehouse.settings.cities')->with('success', 'تم تحديث المدن المخدومة بنجاح.');
    }
}
