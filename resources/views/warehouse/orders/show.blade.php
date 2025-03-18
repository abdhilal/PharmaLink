@extends('layouts.app')
@section('title', 'تفاصيل الطلبية')
@section('content')
    <div class="content">
        <h1>تفاصيل الطلبية #{{ $order->id }}</h1>
        <div class="order-details">
            <p><strong>الصيدلية:</strong> {{ $order->pharmacy->name }}</p>
            <p><strong>السعر الإجمالي:</strong> {{ number_format($order->total_price, 2) }} ريال</p>
            <p><strong>الحالة:</strong> {{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}</p>
            <p><strong>حالة الدين:</strong>
                @php
                    $debt = $order->transactions->where('type', 'debt')->first();
                    $paid = $debt ? 'غير مدفوع' : 'لا يوجد دين';
                @endphp
                {{ $paid }}
            </p>
        </div>

        <h2>العناصر</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الدواء</th>
                    <th>الكمية</th>
                    <th>السعر للوحدة</th>
                    <th>المجموع الفرعي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>{{ $item->medicine->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price_per_unit, 2) }} ريال</td>
                        <td>{{ number_format($item->quantity * $item->price_per_unit, 2) }} ريال</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">لا توجد عناصر في هذه الطلبية</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('orders.index') }}" class="btn btn-primary">العودة إلى الطلبيات</a>
    </div>
@endsection

<style>
    .content { padding: 20px; }
    .order-details { margin-bottom: 20px; }
    .order-details p { margin: 5px 0; }
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
</style>
