@extends('layouts.warehouse.app')

@section('title', 'تفاصيل الطلبية')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">تفاصيل الطلبية العاجلة رقم: {{ $order->id }}</h5>
            </div>
            <div class="card-body">
                <!-- معلومات الطلبية -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>اسم الصيدلية:</strong> {{ $pharmacy->name }}</p>
                        <p class="mb-2"><strong>المدينة:</strong> {{ $pharmacy->city->name }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-2"><strong>حالة الطلب:</strong>
                            <span class="badge {{ $order->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                {{ $order->status === 'pending' ? 'معلق' : $order->status }}
                            </span>
                        </p>
                        <p class="mb-2"><strong>ملاحظات:</strong> {{ $order->note ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>

                <!-- جدول العناصر -->
                <h6 class="fw-bold mb-3">العناصر المطلوبة:</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>اسم الدواء</th>
                                <th>الكمية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        لا توجد عناصر في هذه الطلبية
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="mt-4 d-flex gap-3">
                    <form action="{{ route('warehouse.urgentorder.approve', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-check me-1"></i> قبول الطلبية وإنشائها
                        </button>
                    </form>
                    <a href="{{ route('warehouse.urgentorder.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* تحسينات التصميم */
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .card.shadow-sm {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .card-header.bg-primary {
        background-color: #3B82F6 !important;
        padding: 1rem 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .card-body {
        padding: 1.5rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8fafc;
        color: #4a5568;
        font-weight: 600;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
        transition: background-color 0.3s ease;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .btn-success, .btn-secondary {
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn i {
        font-size: 1rem;
    }
</style>
@endsection
