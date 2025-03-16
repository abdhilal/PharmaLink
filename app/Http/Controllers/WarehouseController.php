<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
   

    public function index()
    {
        $warehouses = Warehouse::whereHas('cities', function ($query) {
            $query->where('city_id', auth()->user()->city_id);
        })->get();
        return view('warehouse.index', compact('warehouses'));
    }
}