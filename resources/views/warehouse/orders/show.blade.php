@extends('layouts.warehouse.app')
@section('title', 'تفاصيل الطلبية - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> تفاصيل الطلبية #{{ $order->id }}</h5>
    </div>
    <p></p>
    <div class="card-body">
        <!-- معلومات الطلبية -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="info-item"><strong><i class="fas fa-store me-2"></i> الصيدلية:</strong> {{ $order->pharmacy->name }}</p>
                <p class="info-item">
                    <strong><i class="fas fa-info-circle me-2"></i> الحالة:</strong>
                    <span class="badge {{ $order->status === 'pending' ? 'bg-warning' : ($order->status === 'delivered' ? 'bg-success' : 'bg-danger') }}">
                        {{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p class="info-item"><strong><i class="fas fa-calendar-alt me-2"></i> التاريخ:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
                <p class="info-item"><strong><i class="fas fa-dollar-sign me-2"></i> السعر الإجمالي:</strong> {{ number_format($order->total_price, 2) }}</p>
            </div>
        </div>

        <!-- جدول العناصر -->
        <h6 class="mt-4 mb-3"><i class="fas fa-list me-2"></i> العناصر</h6>
        <div class="table-responsive">
            <table class="table table-modern">
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
                            <td>{{ $item->medicine->company->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->medicine->selling_price, 2) }} $</td>
                            <td>{{ number_format($item->subtotal, 2) }} $</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد عناصر</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- زر الرجوع -->
        <div class="mt-4">
            <a href="{{ route('warehouse.orders.index') }}" class="btn btn-primary"><i class="fas fa-arrow-right me-2"></i> رجوع</a>
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

    /* معلومات الطلبية */
    .info-item {
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .info-item strong {
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
