<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use Illuminate\Http\Request;

class SupplyOrderController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::all();
        return view('supply_orders.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'discount_type' => 'required|in:per_item,total',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.company_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'numeric|min:0|max:100',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);

        // إنشاء الطلبية
        $supplyOrder = SupplyOrder::create([
            'supplier_id' => $supplier->id,
            'total_quantity' => $request->total_quantity,
            'total_cost_before_discount' => $request->total_cost_before_discount,
            'total_cost_after_discount' => $request->total_cost_after_discount,
            'discount_type' => $request->discount_type,
            'discount_percentage' => $request->discount_type === 'total' ? $request->discount_percentage : null,
            'discount_amount' => $request->discount_amount,
            'order_date' => $request->order_date,
            'note' => $request->note,
        ]);

        // إضافة الأصناف
        foreach ($request->items as $item) {
            SupplyOrderItem::create([
                'supply_order_id' => $supplyOrder->id,
                'name' => $item['name'],
                'company_name' => $item['company_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_percentage' => $request->discount_type === 'per_item' ? ($item['discount_percentage'] ?? 0) : 0,
                'discount_amount' => $request->discount_type === 'per_item' ? ($item['quantity'] * $item['unit_price'] * ($item['discount_percentage'] ?? 0) / 100) : 0,
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        // تحديث المورد
        $supplier->increment('total_orders');
        $supplier->debt += $supplyOrder->total_cost_after_discount;
        $supplier->total_discounts += $supplyOrder->discount_amount;
        $supplier->balance = $supplier->debt - $supplier->total_paid;
        $supplier->save();

        return redirect()->route('supply_orders.create')->with('success', 'Supply order created successfully');
    }
}
