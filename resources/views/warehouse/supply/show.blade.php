@extends('layouts.warehouse.app')

@section('title', 'تفاصيل المورد - PharmaLink')

@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>تفاصيل المورد: {{ $supplier->name }}</span>
        <a href="{{ route('warehouse.suppliers.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع إلى القائمة
        </a>
    </h5>
    <div class="card-body">
        <!-- معلومات المورد -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="mb-3">معلومات أساسية</h6>
                <ul class="list-unstyled">
                    <li><strong>الاسم:</strong> {{ $supplier->name }}</li>
                    <li><strong>رقم الهاتف:</strong> {{ $supplier->phone ?? 'غير متوفر' }}</li>
                    <li><strong>العنوان:</strong> {{ $supplier->address ?? 'غير متوفر' }}</li>
                    <li><strong>تاريخ الإضافة:</strong> {{ $supplier->created_at->format('d/m/Y') }}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">الحالة المالية</h6>
                <ul class="list-unstyled">
                    <li><strong>عدد الطلبيات:</strong> {{ $supplier->total_orders }}</li>
                    <li><strong>إجمالي المدفوع:</strong> {{ number_format($supplier->total_paid, 2) }}</li>
                    <li><strong>إجمالي الخصومات:</strong> {{ number_format($supplier->total_discounts, 2) }}</li>
                    <li>
                        @if($supplier->balance > 0)
                            <span class="badge bg-warning">مدين: {{ number_format($supplier->balance, 2) }}</span>
                        @elseif($supplier->balance < 0)
                            <span class="badge bg-success">دائن: {{ number_format(abs($supplier->balance), 2) }}</span>
                        @else
                            <span class="badge bg-info">متوازن</span>
                        @endif
                        <strong>:الرصيد</strong>
                    </li>
                </ul>
            </div>
        </div>

        <!-- نموذج تسجيل دفعة -->
        <h6 class="mb-3">تسجيل دفعة جديدة</h6>
        <form action="{{ route('warehouse.supplier_payments.store', $supplier->id) }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="payment_date" class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                    @error('payment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="note" class="form-label">ملاحظات</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="1">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-money me-1"></i> تسجيل الدفعة
            </button>
        </form>

        <!-- قائمة الطلبيات -->
        <h6 class="mb-3">الطلبيات المرتبطة</h6>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>تاريخ الطلب</th>
                        <th>الكمية الإجمالية</th>
                        <th>التكلفة قبل الخصم</th>
                        <th style="background-color: #efefef">التكلفة بعد الخصم</th>
                        <th>نوع الخصم</th>
                        <th>قيمة الخصم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplier->supplyOrders as $index => $order)
                    <tr style="cursor: pointer;" onclick="window.location='{{ route('warehouse.supplier_order.show', $order->id) }}'">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->order_date }}</td> <!-- تصحيح تاريخ الطلب -->
                        <td>{{ $order->total_quantity }}</td>
                        <td>{{ number_format($order->total_cost_before_discount, 2) }}</td>
                        <td style="background-color: #efefef">{{ number_format($order->total_cost_after_discount, 2) }}</td>
                        <td>{{ $order->discount_type == 'per_item' ? 'لكل صنف' : 'على الإجمالي' }}</td>
                        <td>{{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد طلبيات مرتبطة بهذا المورد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- قائمة المدفوعات -->
        <h6 class="mt-4 mb-3">سجل المدفوعات</h6>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>تاريخ الدفع</th>
                        <th>المبلغ</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplier->payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->note ?? 'لا توجد ملاحظات' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">لا توجد مدفوعات مسجلة لهذا المورد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // يمكن إضافة جافا سكريبت لاحقًا إذا أردت تفاعلًا إضافيًا
</script>
@endsection
