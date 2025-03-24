@extends('layouts.warehouse.app')
@section('title', 'تفاصيل الموظف - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">تفاصيل الموظف: {{ $employee->name }}</h5>
    <div class="card-body">
        <p><strong>المنصب:</strong> {{ $employee->position }}</p>
        <p><strong>الراتب:</strong> {{ number_format($employee->salary, 2) }} ريال</p>
        <p><strong>تاريخ التوظيف:</strong> {{ $employee->date }}</p>
        <p><strong>الحالة:</strong> {{ $employee->status === 'active' ? 'نشط' : 'غير نشط' }}</p>
        <p><strong>الهاتف:</strong> {{ $employee->phone ?? 'غير متوفر' }}</p>

        <h6 class="mt-4">دفعات الرواتب</h6>
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
                @forelse ($employee->payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ number_format($payment->amount, 2) }} ريال</td>
                    <td>{{ $payment->date }}</td>
                    <td>{{ $payment->note ?? 'راتب شهري' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">لا توجد دفعات</td></tr>
                @endforelse
            </tbody>
        </table>

        <h6 class="mt-4">تسجيل دفعة راتب</h6>
        <form action="{{ route('warehouse.employees.pay', $employee->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">المبلغ</label>
                <input type="number" name="amount" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ الدفع</label>
                <input type="date" name="date" class="form-control" value="{{now()}}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="note" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">تسجيل الدفعة</button>
        </form>
    </div>
</div>
@endsection
