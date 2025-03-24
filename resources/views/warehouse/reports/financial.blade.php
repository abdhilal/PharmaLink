@extends('layouts.warehouse.app')
@section('title', 'التقرير المالي الشامل - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">التقرير المالي الشامل</h5>
    <div class="card-body">
        <!-- نموذج التصفية حسب التاريخ -->
        <form method="GET" action="{{ route('warehouse.financial_report') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">تصفية</button>
                </div>
            </div>
        </form>

        <!-- ملخص التقرير -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>إجمالي المبيعات</h6>
                        <h4>{{ number_format($totalSales, 2) }} ريال</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>الإيرادات النقدية</h6>
                        <h4>{{ number_format($cashIncome, 2) }} ريال</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>إجمالي المصاريف</h6>
                        <h4>{{ number_format($totalExpenses, 2) }} ريال</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h6>صافي الربح</h6>
                        <h4>{{ number_format($netProfit, 2) }} ريال</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>ديون الصيدليات</h6>
                        <h4>{{ number_format($pharmacyDebts->sum('balance'), 2) }} ريال</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>ديون الموردين</h6>
                        <h4>{{ number_format($supplierDebts->sum('debt'), 2) }} ريال</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل المصاريف -->
        <h6 class="mb-3">تفاصيل المصاريف</h6>
        <table class="table table-hover mb-4">
            <thead>
                <tr>
                    <th>الفئة</th>
                    <th>المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>مصاريف عامة</td>
                    <td>{{ number_format($expenseDetails->general_expenses, 2) }} ريال</td>
                </tr>
                <tr>
                    <td>رواتب الموظفين</td>
                    <td>{{ number_format($expenseDetails->employee_expenses, 2) }} ريال</td>
                </tr>
                <tr>
                    <td>دفعات الموردين</td>
                    <td>{{ number_format($expenseDetails->supplier_expenses, 2) }} ريال</td>
                </tr>
            </tbody>
        </table>

        <!-- ديون الصيدليات -->
        <h6 class="mb-3">ديون الصيدليات</h6>
        <table class="table table-hover mb-4">
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
                    <td>{{ number_format($debt->balance, 2) }} ريال</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">لا توجد ديون مستحقة</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- ديون الموردين -->
        <h6 class="mb-3">ديون الموردين</h6>
        <table class="table table-hover">
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
                    <td>{{ number_format($debt->debt, 2) }} ريال</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">لا توجد ديون للموردين</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
