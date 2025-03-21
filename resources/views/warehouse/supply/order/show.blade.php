@extends('layouts.warehouse.app')

@section('title', 'عناصر الطلبية - PharmaLink')

@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>عناصر الطلبية  - المورد: {{ $supplyOrder->supplier->name }}</span>
        <a href="{{ route('warehouse.suppliers.show', $supplyOrder->supplier->id) }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع إلى تفاصيل المورد
        </a>
    </h5>
    <div class="card-body">
        <!-- معلومات الطلبية -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="mb-3">معلومات الطلبية</h6>
                <ul class="list-unstyled">
                    <li><strong>تاريخ الطلب:</strong> {{ $supplyOrder->order_date }}</li>
                    <li><strong>الكمية الإجمالية:</strong> {{ $supplyOrder->total_quantity }}</li>
                    <li><strong>التكلفة قبل الخصم:</strong> {{ number_format($supplyOrder->total_cost_before_discount, 2) }}</li>
                    <li><strong>التكلفة بعد الخصم:</strong> {{ number_format($supplyOrder->total_cost_after_discount, 2) }}</li>
                    <li><strong>قيمة الخصم:</strong> {{ number_format($supplyOrder->discount_amount, 2) }}</li>
                    <li><strong>نوع الخصم:</strong> {{ $supplyOrder->discount_type == 'per_item' ? 'لكل صنف' : 'على الإجمالي' }}</li>
                </ul>
            </div>
        </div>

        <!-- جدول عناصر الطلبية -->
        <h6 class="mb-3">عناصر الطلبية</h6>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الدواء</th>
                        <th>الشركة</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>نسبة الخصم (%)</th>
                        <th>قيمة الخصم</th>
                        <th>المجموع الفرعي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplyOrder->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->medicine->name }}</td>
                        <td>{{ $item->medicine->company->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->discount_percentage, 2) }}%</td>
                        <td>{{ number_format($item->discount_amount, 2) }}</td>
                        <td>{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد عناصر مرتبطة بهذه الطلبية</td>
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
