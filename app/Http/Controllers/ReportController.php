<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Supplier;
use App\Models\WarehouseCash;
use App\Models\WarehouseExpense;
use App\Models\EmployeePayment;
use App\Models\SupplierPayment;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function index()
    {
        // إحصائيات عامة
        $totalSales = Order::whereYear('created_at', Carbon::now()->year)->sum('total');
        $totalOrders = Order::count();
        $activePharmacies = Pharmacy::has('orders')->count();

        // أكثر الأدوية مبيعاً
        $topMedicines = Medicine::select('medicines.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'medicines.id', '=', 'order_items.medicine_id')
            ->groupBy('medicines.id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // تقارير المبيعات الشهرية
        $monthlySales = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total_sales'),
            DB::raw('COUNT(*) as order_count')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // أداء الصيدليات
        $pharmacyPerformance = Pharmacy::select(
            'pharmacies.*',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(orders.total) as total_spent')
        )
            ->leftJoin('orders', 'pharmacies.id', '=', 'orders.pharmacy_id')
            ->groupBy('pharmacies.id')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // حركة المخزون
        $lowStock = Medicine::where('quantity', '<=', 'min_quantity')
            ->orderBy('quantity')
            ->limit(10)
            ->get();

        return view('warehouse.reports.index', compact(
            'totalSales',
            'totalOrders',
            'activePharmacies',
            'topMedicines',
            'monthlySales',
            'pharmacyPerformance',
            'lowStock'
        ));
    }

    public function salesReport(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now());

        $query = Order::whereBetween('created_at', [$startDate, $endDate]);

        $sales = match ($period) {
            'daily' => $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
                ->groupBy('date')
                ->orderBy('date'),
            'monthly' => $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month'),
            'yearly' => $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
                ->groupBy('year')
                ->orderBy('year'),
            default => $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
                ->groupBy('date')
                ->orderBy('date'),
        };

        $sales = $sales->get();

        return view('warehouse.reports.sales', compact('sales', 'period', 'startDate', 'endDate'));
    }

    public function inventoryReport()
    {
        $medicines = Medicine::select(
            'medicines.*',
            DB::raw('(SELECT SUM(quantity) FROM order_items WHERE medicine_id = medicines.id) as total_sold')
        )
            ->withCount(['orderItems as monthly_sales' => function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            }])
            ->get();

        return view('warehouse.reports.inventory', compact('medicines'));
    }

    public function pharmacyReport(Request $request, Pharmacy $pharmacy)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(6));
        $endDate = $request->input('end_date', Carbon::now());

        $orders = $pharmacy->orders()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSpent = $orders->sum('total');
        $averageOrderValue = $orders->avg('total');
        $orderCount = $orders->count();

        $mostOrderedMedicines = Medicine::select(
            'medicines.*',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('COUNT(DISTINCT orders.id) as order_count')
        )
            ->join('order_items', 'medicines.id', '=', 'order_items.medicine_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.pharmacy_id', $pharmacy->id)
            ->groupBy('medicines.id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return view('warehouse.reports.pharmacy', compact(
            'pharmacy',
            'orders',
            'totalSpent',
            'averageOrderValue',
            'orderCount',
            'mostOrderedMedicines',
            'startDate',
            'endDate'
        ));
    }
}
