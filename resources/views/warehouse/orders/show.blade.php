@extends('layouts.warehouse.app')
@section('title', 'تفاصيل الطلبية - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">تفاصيل الطلبية #{{ $order->id }}</h5>
    <div class="card-body">
        <p><strong>الصيدلية:</strong> {{ $order->pharmacy->name }}</p>
        <p><strong>الحالة:</strong> {{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}</p>
        <p><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
        <p><strong>السعر الإجمالي:</strong> {{ number_format($order->total_price, 2) }} ريال</p>

        <h6 class="mt-4">العناصر</h6>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الدواء</th>
                    <th>الشركة</th>
                    <th>الكمية</th>
                    <th>السعر للوحدة</th>
                    <th>المجموع الفرعي</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->medicine->name }}</td>
                    <td>{{ $item->medicine->company->name}}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price_per_unit, 2) }} ريال</td>
                    <td>{{ number_format($item->subtotal, 2) }} ريال</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">لا توجد عناصر</td></tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('warehouse.orders.index') }}" class="btn btn-primary">رجوع</a>
    </div>
</div>
@endsection
