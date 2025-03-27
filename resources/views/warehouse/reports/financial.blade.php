@extends('layouts.warehouse.app')
@section('title', 'التقرير المالي الشامل - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> التقرير المالي الشامل</h5>
    </div>
    <p></p>
    <div class="card-body">
        <!-- نموذج التصفية حسب التاريخ -->
        <form method="GET" action="{{ route('warehouse.financial_report') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-calendar me-2"></i> من تاريخ</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-calendar me-2"></i> إلى تاريخ</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i> تصفية</button>
                </div>
            </div>
        </form>

        <!-- ملخص التقرير -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-shopping-bag me-2"></i> إجمالي المبيعات</h6>
                        <h4>{{ number_format($totalSales, 2) }} $</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-money-bill me-2"></i> الإيرادات النقدية</h6>
                        <h4>{{ number_format($cashIncome, 2) }} $</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-minus-circle me-2"></i> إجمالي المصاريف</h6>
                        <h4>{{ number_format($totalExpenses, 2) }} $</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-chart-pie me-2"></i> صافي الربح</h6>
                        <h4>{{ number_format($netProfit, 2) }} $</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card bg-warning text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-store-alt me-2"></i> ديون الصيدليات</h6>
                        <h4>{{ number_format($pharmacyDebts->sum('balance'), 2) }} $</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <h6><i class="fas fa-truck me-2"></i> ديون الموردين</h6>
                        <h4>{{ number_format($supplierDebts->sum('debt'), 2) }} $</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل المصاريف -->
        <h6 class="mb-3"><i class="fas fa-list-alt me-2"></i> تفاصيل المصاريف</h6>
        <div class="table-responsive mb-4">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>الفئة</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>مصاريف عامة</td>
                        <td>{{ number_format($expenseDetails->general_expenses, 2) }} $</td>
                    </tr>
                    <tr>
                        <td>رواتب الموظفين</td>
                        <td>{{ number_format($expenseDetails->employee_expenses, 2) }} $</td>
                    </tr>
                    <tr>
                        <td>دفعات الموردين</td>
                        <td>{{ number_format($expenseDetails->supplier_expenses, 2) }} $</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ديون الصيدليات -->
        <h6 class="mb-3"><i class="fas fa-store me-2"></i> ديون الصيدليات</h6>
        <div class="table-responsive mb-4">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>الصيدلية</th>
                        <th>الدين المستحق</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pharmacyDebts as $debt)
                        <tr>
                            <td>{{ $debt->pharmacy->name ?? 'غير محدد' }}</td>
                            <td>{{ number_format($debt->balance, 2) }} $</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">لا توجد ديون مستحقة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ديون الموردين -->
        <h6 class="mb-3"><i class="fas fa-truck me-2"></i> ديون الموردين</h6>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>المورد</th>
                        <th>الدين المستحق</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplierDebts as $debt)
                        <tr>
                            <td>{{ $debt->name }}</td>
                            <td>{{ number_format($debt->debt, 2) }} $</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">لا توجد ديون للموردين</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<style>
    /* الأساسيات */
    body {
        background-color: #f5f7fa;
        font-family: 'Arial', sans-serif;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
    }

    .card-header {
        padding: 15px 20px;
        font-weight: 600;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #0288d1, #4fc3f7);
    }

    /* نموذج التصفية */
    .form-control {
        border-radius: 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 500;
        color: #343a40;
    }

    /* ملخص التقرير */
    .card.bg-success, .card.bg-info, .card.bg-danger, .card.bg-secondary, .card.bg-warning, .card.bg-primary {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .card.bg-success:hover, .card.bg-info:hover, .card.bg-danger:hover, .card.bg-secondary:hover, .card.bg-warning:hover, .card.bg-primary:hover {
        transform: translateY(-5px);
    }

    .card-body h6 {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .card-body h4 {
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0;
    }

    /* الجداول */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table-modern th {
        background-color: #e9ecef;
        color: #343a40;
        font-weight: 600;
        padding: 15px;
        text-align: center;
    }

    .table-modern td {
        background-color: #fff;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border-radius: 8px;
    }

    .table-modern tbody tr:hover td {
        background-color: #f8f9fa;
        transition: background-color 0.3s;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* الأزرار */
    .btn {
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
