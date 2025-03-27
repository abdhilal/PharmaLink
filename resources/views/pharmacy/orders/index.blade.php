@extends('layouts.pharmacy.app')
@section('title', 'الطلبيات - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> الطلبيات</h5>
    </div>
    <p></p>
    <div class="card-body">
        <!-- نموذج البحث -->
        <form method="GET" action="{{ route('pharmacy.orders.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلقة</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>مسلمة</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغاة</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> بحث</button>
                </div>
            </div>
        </form>

        <!-- جدول الطلبيات -->
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستودع</th>
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
                            <td>{{ $order->warehouse->user->name }}</td>
                            <td>
                                <span class="badge {{ $order->status === 'pending' ? 'bg-warning' : ($order->status === 'delivered' ? 'bg-success' : 'bg-danger') }}">
                                    {{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total_price, 2) }} $</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pharmacy.orders.show', $order->id) }}" class="btn btn-sm btn-info mb-10" title="تفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($order->status === 'pending')

                                        <form action="{{ route('pharmacy.orders.destroy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد؟');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                               

                                    @endif
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد طلبيات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- التصفح -->
        <div class="mt-4">
            {{ $orders->links() }}
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

    /* نموذج البحث */
    .form-control, .form-select {
        border-radius: 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 500;
        color: #343a40;
    }

    /* الجدول */
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

    .btn-group .btn {
        border-radius: 5px;
        padding: 6px 12px;
    }

    /* البادج */
    .badge {
        padding: 6px 12px;
        font-size: 0.9rem;
        border-radius: 20px;
    }

    .bg-warning {
        background-color: #ffca28;
        color: #212529;
    }

    .bg-success {
        background-color: #28a745;
        color: #fff;
    }

    .bg-danger {
        background-color: #dc3545;
        color: #fff;
    }
</style>
