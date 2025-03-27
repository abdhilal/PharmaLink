@extends('layouts.warehouse.app')
@section('title', 'تفاصيل الصيدلية - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0"><i class="fas fa-store me-2"></i> تفاصيل الصيدلية: {{ $pharmacy->name }}</h5>
    </div>
    <p></p>
    <div class="card-body">
        <!-- معلومات الصيدلية -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="info-item"><strong><i class="fas fa-city me-2"></i> المدينة:</strong> {{ $pharmacy->city->name ?? 'غير محدد' }}</p>
            </div>
            <div class="col-md-6">
                <p class="info-item">
                    <strong><i class="fas fa-dollar-sign me-2"></i> الدين المستحق:</strong>
                    <span class="badge {{ ($pharmacy->accounts->first()->balance ?? 0) > 0 ? 'bg-danger' : 'bg-success' }}">
                        {{ number_format($pharmacy->accounts->first()->balance ?? 0, 2) }}
                    </span>

                        <a href="{{ route('warehouse.payments.create', $pharmacy->id) }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> تسجيل دفعة
                        </a>

                </p>
            </div>
        </div>

        <!-- زر تسجيل دفعة -->

        <!-- جدول الطلبيات -->
        <h6 class="mt-4 mb-3"><i class="fas fa-shopping-cart me-2"></i> الطلبيات</h6>
        <div class="table-responsive mb-4">
            <table class="table table-modern">
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
                            <td>
                                <span class="badge {{ $order->status === 'pending' ? 'bg-warning' : ($order->status === 'delivered' ? 'bg-success' : 'bg-danger') }}">
                                    {{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total_price, 2) }} $</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">لا توجد طلبيات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- جدول المعاملات المالية -->
        <h6 class="mt-4 mb-3"><i class="fas fa-exchange-alt me-2"></i> المعاملات المالية</h6>
        <div class="table-responsive">
            <table class="table table-modern">
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
                            <td>
                                <span class="badge {{ $transaction->type === 'debt' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $transaction->type === 'debt' ? 'دين' : 'دفعة' }}
                                </span>
                            </td>
                            <td>{{ number_format($transaction->amount, 2) }} $</td>
                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                            <td>{{ $transaction->order_id ? 'طلبية #' . $transaction->order_id : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">لا توجد معاملات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

    /* معلومات الصيدلية */
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
