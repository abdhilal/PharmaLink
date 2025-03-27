<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Account;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    //عرض الصيدليات التي لها طلبيات بالمستودع
    public function index(Request $request)
    {
        $warehouseId = auth()->user()->warehouse->id;

        $query = User::where('role', 'pharmacy')
        ->whereHas('accounts', function ($query) use ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        })
        ->with(['city', 'accounts' => function ($query) use ($warehouseId) {
            $query->where('warehouse_id', $warehouseId)->with('transactions');
        }]);

        // فلترة حسب الاسم أو المدينة
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhereHas('city', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        $pharmacies = $query->withCount(['orders' => function ($query) use ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }])->paginate(10);

        return view('warehouse.pharmacies.index', compact('pharmacies'));
    }



//عرض تفاصيل صيدلي
    public function show(User $pharmacy)
    {
        if ($pharmacy->role !== 'pharmacy') {
            abort(404, 'المستخدم ليس صيدلية.');
        }

        $warehouseId = auth()->user()->warehouse->id;

        $pharmacy->load([
            'city',
            'orders' => function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId)->with('items.medicine');
            },
            'accounts' => function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId)->with('transactions');
            }
        ]);

        if (!$pharmacy->accounts->isNotEmpty()) {
            abort(404, 'لا يوجد حساب مرتبط لهذه الصيدلية مع المستودع.');
        }

        return view('warehouse.pharmacies.show', compact('pharmacy'));
    }


  
}
