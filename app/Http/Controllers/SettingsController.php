<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{


    public function cities()
    {
        // $warehouse = auth()->user()->warehouse;
        // $cities = [1,2,4];
        // $selectedCities = $warehouse->cities->pluck('id')->toArray();

        $city = City::where('user_id', Auth::user()->id)->first();

        if (Auth::user()->role == 'warehouse') {
            return view('warehouse.settings.cities',compact('city'));
        } else {
            return view('pharmacy.settings.cities',compact('city'));
        }
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


    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'range_east' => 'nullable|numeric|min:0',
            'range_west' => 'nullable|numeric|min:0',
            'range_north' => 'nullable|numeric|min:0',
            'range_south' => 'nullable|numeric|min:0',
        ]);


        $city = City::where('user_id', Auth::user()->id)->first();

        if ($city) {

            $city->update([
                'name' => $request->name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'range_east' => $request->range_east,
                'range_west' => $request->range_west,
                'range_north' => $request->range_north,
                'range_south' => $request->range_south,
            ]);
        } else {
            // إذا لم يكن السجل موجودًا، قم بإنشائه
            City::create([
                'name' => $request->name,
                'user_id' => Auth::user()->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'range_east' => $request->range_east,
                'range_west' => $request->range_west,
                'range_north' => $request->range_north,
                'range_south' => $request->range_south,
            ]);
        }
        return redirect()->back()->with('success', 'تم حفظ الموقع بنجاح');
    }


  
}
