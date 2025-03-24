<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\WarehouseCash;
use App\Models\WarehouseExpense;
use App\Models\EmployeePayment;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function financialReport(Request $request)
    {
        $warehouseId = Auth::user()->warehouse->id;

        // تحديد الفترة الزمنية (افتراضي: الشهر الحالي)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. إجمالي المبيعات (الطلبيات المسلمة)
        $totalSales = Order::where('warehouse_id', $warehouseId)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // 2. الإيرادات النقدية (من warehouse_cashes)
        $cashIncome = WarehouseCash::where('warehouse_id', $warehouseId)
            ->where('transaction_type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // 3. الديون المستحقة من الصيدليات
        $pharmacyDebts = Account::where('warehouse_id', $warehouseId)
            ->with('pharmacy')
            ->where('balance', '>', 0)
            ->get(['pharmacy_id', 'balance']);

        // 4. المصاريف الكلية (من warehouse_cashes للنوع expense)
        $totalExpenses = WarehouseCash::where('warehouse_id', $warehouseId)
            ->where('transaction_type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // تفاصيل المصاريف (اختياري)
        $expenseDetails = WarehouseCash::where('warehouse_id', $warehouseId)
            ->where('transaction_type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw("
                                          SUM(CASE WHEN related_type = 'expense' THEN amount ELSE 0 END) as general_expenses,
                                          SUM(CASE WHEN related_type = 'employee_payment' THEN amount ELSE 0 END) as employee_expenses,
                                          SUM(CASE WHEN related_type = 'supplier_payment' THEN amount ELSE 0 END) as supplier_expenses
                                      ")
            ->first();

        // 5. ديون الموردين
        $supplierDebts = Supplier::where('warehouse_id', $warehouseId)
            ->where('debt', '>', 0)
            ->get(['id', 'name', 'debt']);

        // 6. الخصومات الممنوحة للموردين
        $totalDiscounts = Supplier::where('warehouse_id', $warehouseId)
            ->sum('total_discounts');

        // 7. صافي الربح (الإيرادات النقدية - المصاريف) مع المبيعات غير النقدية 
        $netProfit = ($cashIncome + $totalSales) - $totalExpenses;

        return view('warehouse.reports.financial', compact(
            'totalSales',
            'cashIncome',
            'pharmacyDebts',
            'totalExpenses',
            'expenseDetails',
            'supplierDebts',
            'totalDiscounts',
            'netProfit',
            'startDate',
            'endDate'
        ));
    }
}
