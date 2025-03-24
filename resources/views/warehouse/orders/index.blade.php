@extends('layouts.warehouse.app')
@section('title', 'الطلبيات - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">الطلبيات</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('warehouse.orders.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="ابحث باسم الصيدلية" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلقة</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>مسلمة</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الصيدلية</th>
                    <th>الحالة</th>
                    <th>السعر</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $index => $order)
                <tr>
                    <td>{{ $orders->firstItem() + $index }}</td>
                    <td>{{ $order->pharmacy->name }}</td>
                    <td>{{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}</td>
                    <td>{{ number_format($order->total_price, 2) }} ريال</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('warehouse.orders.show', $order->id) }}" class="btn btn-sm btn-info">تفاصيل</a>

                        @if ($order->status === 'pending')
                            <form action="{{ route('warehouse.orders.approve', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('هل أنت متأكد من التسليم؟')">تسليم</button>
                            </form>
                            <form action="{{ route('warehouse.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                            </form>
                        @elseif ($order->status === 'delivered')
                            <form action="{{ route('warehouse.orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الإلغاء؟')">إلغاء</button>
                            </form>
                            <a href="{{ route('warehouse.orders.edit', $order->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                            
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">لا توجد طلبيات</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>
</div>
@endsection
