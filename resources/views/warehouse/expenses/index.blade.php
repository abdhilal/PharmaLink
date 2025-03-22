@extends('layouts.warehouse.app')
@section('title', 'مصاريف المستودع - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>مصاريف المستودع</span>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
            <i class="bx bx-plus me-1"></i> إضافة مصروف
        </button>
    </h5>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <h6>إجمالي المصاريف: {{ number_format($totalExpenses, 2) }} ريال</h6>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $index => $expense)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->date }}</td>
                        <td>{{ $expense->note ?? 'غير متوفر' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">لا توجد مصاريف</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal لإضافة مصروف -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('warehouse.expenses.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مصروف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="note"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المبلغ</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" class="form-control" name="date" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">تسجيل</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
