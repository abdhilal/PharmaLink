@extends('layouts.warehouse.app')
@section('title', 'تفاصيل الصيدلية - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">تفاصيل الصيدلية: {{ $pharmacy->name }}</h5>
    <div class="card-body">
        <p><strong>المدينة:</strong> {{ $pharmacy->city->name ?? 'غير محدد' }}</p>
        <p><strong>الدين المستحق:</strong> {{ number_format($pharmacy->accounts->first()->balance ?? 0, 2) }} ريال</p>

        <h6 class="mt-4">الطلبيات</h6>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>السعر</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pharmacy->orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ number_format($order->total_price, 2) }} ريال</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">لا توجد طلبيات</td></tr>
                @endforelse
            </tbody>
        </table>

        <h6 class="mt-4">المعاملات المالية</h6>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>التاريخ</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pharmacy->accounts->first()->transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->type === 'debt' ? 'دين' : 'دفعة' }}</td>
                    <td>{{ number_format($transaction->amount, 2) }} ريال</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    <td>{{ $transaction->order_id ? 'طلبية #' . $transaction->order_id : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">لا توجد معاملات</td></tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('warehouse.payments.create', $pharmacy->id) }}" class="btn btn-success mt-3">تسجيل دفعة</a>
    </div>
</div>
@endsection
