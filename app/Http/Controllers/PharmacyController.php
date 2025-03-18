<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{


    // عرض قائمة الصيدليات
    public function index()
    {
        $warehouseId = auth()->user()->warehouse->id;
        $pharmacies = User::where('role', 'pharmacy')
            ->whereHas('accounts', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->with('city')
            ->get();

        return view('warehouse.pharmacies.index', compact('pharmacies'));
    }

    // عرض تفاصيل صيدلية معينة
    public function show(User $pharmacy)
    {
        if ($pharmacy->role !== 'pharmacy') {
            abort(404, 'المستخدم ليس صيدلية.');
        }

        $warehouseId = auth()->user()->warehouse->id;

        // جلب كل البيانات دفعة واحدة مع التحقق من المستودع
        $pharmacy->load([
            'city',
            'orders' => function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            },
            'accounts' => function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId)->with('transactions');
            }
        ]);

        // التحقق من وجود حساب مرتبط
        if (!$pharmacy->accounts->isNotEmpty()) {
            abort(404, 'لا يوجد حساب مرتبط لهذه الصيدلية مع المستودع.');
        }

        return view('warehouse.pharmacies.show', compact('pharmacy'));
    }
}
