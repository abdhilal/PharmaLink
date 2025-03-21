@extends('layouts.warehouse.app')

@section('title', 'عرض الموردين - PharmaLink')

@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>قائمة الموردين</span>
    </h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المورد</th>
                    <th>رقم الهاتف</th>
                    <th>العنوان</th>
                    <th>عدد الطلبيات</th>
                    <th>الدين</th>
                    <th>إجمالي المدفوع</th>
                    <th>الرصيد</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($suppliers as $index => $supplier)
                <tr style="cursor: pointer;" onclick="window.location='{{ route('warehouse.suppliers.show', $supplier->id) }}'; event.stopPropagation();">
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $supplier->name }}</strong></td>
                    <td>{{ $supplier->phone ?? 'غير متوفر' }}</td>
                    <td>{{ $supplier->address ?? 'غير متوفر' }}</td>
                    <td>{{ $supplier->total_orders }}</td>
                    <td>{{ number_format($supplier->debt, 2) }}</td>
                    <td>{{ number_format($supplier->total_paid, 2) }}</td>
                    <td>
                        @if($supplier->balance > 0)
                            <span class="badge bg-warning">مدين: {{ number_format($supplier->balance, 2) }}</span>
                        @elseif($supplier->balance < 0)
                            <span class="badge bg-success">دائن: {{ number_format(abs($supplier->balance), 2) }}</span>
                        @else
                            <span class="badge bg-info">متوازن</span>
                        @endif
                    </td>
                    <td onclick="event.stopPropagation();">
                        <a href="{{ route('warehouse.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-edit-alt me-1"></i> تعديل
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">لا توجد بيانات للموردين حاليًا</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // منع النقر على القائمة المنسدلة من تحويل الصف بأكمله
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
</script>
@endsection
