@extends('layouts.warehouse.app')
@section('title', 'تسجيل دفعة - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">تسجيل دفعة من الصيدلية: {{ $pharmacy->name }}</h5>
    <div class="card-body">
        <p><strong>الرصيد المستحق:</strong> {{ number_format($account->balance, 2) }} $</p>
        <form action="{{ route('warehouse.payments.store', $pharmacy->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">المبلغ</label>
                <input type="number" name="amount" class="form-control" step="0.01" max="{{ $account->balance }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ الدفع</label>
                <input type="date" name="payment_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">طريقة الدفع</label>
                <select name="payment_method" class="form-select" required>
                    <option value="cash">نقدي</option>

                </select>
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
