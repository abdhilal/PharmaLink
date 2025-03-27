
@extends('layouts.pharmacy.app')
@section('title', 'السلة')
@section('content')
<div class="content">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- عنوان الصفحة -->
        <h1 class="mb-4 text-center animate__animated animate__fadeIn">
            <i class="fas fa-shopping-cart me-2"></i> سلة التسوق الخاصة بك
        </h1>

        <!-- فواتير المستودعات -->
        @foreach($invoices as $warehouseId => $invoice)
            <div class="invoice card shadow-sm border-0 mb-5 animate__animated animate__fadeInUp">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i> المستودع رقم {{ $warehouseId }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>الدواء</th>
                                    <th>السعر/الوحدة</th>
                                    <th>الكمية</th>
                                    <th>المجموع الفرعي</th>
                                    <th>الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice['items'] as $item)
                                    <tr>
                                        <td>{{ $item['medicine']->name }}</td>
                                        <td>{{ number_format($item['price'], 2) }} $</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>{{ number_format($item['subtotal'], 2) }} $</td>
                                        <td>
                                            <!-- نموذج التحديث -->
                                            <form action="{{ route('pharmacy.cart.update') }}" method="POST" class="d-inline-block me-2">
                                                @csrf
                                                <input type="hidden" name="medicine_id" value="{{ $item['medicine']->id }}">
                                                <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['medicine']->quantity }}" class="form-control quantity-input">
                                                    <button type="submit" class="btn btn-outline-primary">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </form>
                                            <!-- نموذج الحذف -->
                                            <form action="{{ route('pharmacy.cart.remove') }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <input type="hidden" name="medicine_id" value="{{ $item['medicine']->id }}">
                                                <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-end mt-3 fw-bold">
                        <i class="fas -bill-wave me-2"></i> $ الإجمالي: {{ number_format($invoice['total'], 2) }}
                    </p>
                </div>
            </div>
        @endforeach

        <!-- زر الدفع أو رسالة السلة الفارغة -->
        @if(!empty($invoices))
            <form method="POST" action="{{ route('pharmacy.cart.checkout') }}" class="text-center">
                @csrf
                <button type="submit" class="btn btn-success btn-lg checkout-btn">
                    <i class="fas fa-check-circle me-2"></i> إتمام الشراء
                </button>
            </form>
        @else
            <div class="alert alert-info text-center shadow-sm animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle me-2"></i> سلتك فارغة حاليًا.
            </div>
        @endif
    </div>
</div>

<style>
    /* الأساسيات */
    body {
        background-color: #f5f7fa;
        font-family: 'Arial', sans-serif;
    }

    .content {
        padding: 20px;
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

    /* تنسيق حقل الكمية */
    .quantity-input {
        text-align: center;
        border-radius: 8px 0 0 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .input-group .btn-outline-primary {
        border-radius: 0 8px 8px 0;
        padding: 6px 10px;
    }

    .input-group .btn-outline-primary:hover {
        background-color: #0277bd;
        color: #fff;
    }

    /* الأزرار */
    .btn {
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.9rem;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
    }

    .btn-success:hover {
        background-color: #218838;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }

    .btn-danger {
        background-color: #e74c3c;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c0392b;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }

    .checkout-btn {
        padding: 12px 30px;
        font-size: 1.1rem;
    }

    /* التنبيه */
    .alert-info {
        background: #cce5ff;
        color: #004085;
        border-radius: 10px;
        padding: 15px;
    }
</style>
@endsection
