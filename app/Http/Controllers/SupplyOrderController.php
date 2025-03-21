<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use Illuminate\Http\Request;

class SupplyOrderController extends Controller
{

    public function show($id)
    {
        $supplyOrder = SupplyOrder::with(['items.medicine', 'supplier'])
            ->whereHas('supplier', function ($query) {
                $query->where('warehouse_id', auth()->user()->warehouse->id);
            })
            ->findOrFail($id);

        return view('warehouse.supply.order.show', compact('supplyOrder'));

    }

    public function store(Request $request){}

}
