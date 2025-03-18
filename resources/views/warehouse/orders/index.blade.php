@extends('layouts.app')
@section('title', 'عرض الطلبيات')
@section('content')
    <div class="content">
        <h1>الطلبيات</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>الصيدلية</th>
                    <th>السعر الإجمالي</th>
                    <th>الحالة</th>
                    <th>حالة الدين</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order) }}">{{ $order->id }}</a>
                        </td>
                        <td>{{ $order->pharmacy->name }}</td>
                        <td>{{ number_format($order->total_price, 2) }} ريال</td>
                        <td>{{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}</td>
                        <td>
                            @php
                                $debt = $order->transactions->where('type', 'debt')->first();
                                $paid = $debt ? 'غير مدفوع' : 'لا يوجد دين';
                            @endphp
                            {{ $paid }}
                        </td>
                        <td>
                            @if($order->status === 'pending')
                                <form action="{{ route('orders.approve', $order) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('هل أنت متأكد من الموافقة؟')">موافقة</button>
                                </form>
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">تعديل</a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                                </form>
                            @elseif($order->status === 'delivered')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('هل أنت متأكد من الإلغاء؟')">إلغاء</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">لا توجد طلبيات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<style>
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 5px 10px; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px; }
    .btn-success { background-color: #28a745; }
    .btn-primary { background-color: #007bff; }
    .btn-danger { background-color: #dc3545; }
    .btn-warning { background-color: #ffc107; }
    .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
