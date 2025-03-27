@extends('layouts.warehouse.app')
@section('title', 'عرض الموردين - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-truck me-2"></i> قائمة الموردين</h5>
    </div>
    <div class="card-body">
        <!-- جدول الموردين -->
        <div class="table-responsive text-nowrap">
            <table class="table table-modern">
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
                <tbody>
                    @forelse ($suppliers as $index => $supplier)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('warehouse.suppliers.show', $supplier->id) }}'; event.stopPropagation();">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $supplier->name }}</strong></td>
                            <td>{{ $supplier->phone ?? 'غير متوفر' }}</td>
                            <td>{{ $supplier->address ?? 'غير متوفر' }}</td>
                            <td>{{ $supplier->total_orders }}</td>
                            <td>{{ number_format($supplier->debt, 2) }} $</td>
                            <td>{{ number_format($supplier->total_paid, 2) }} $</td>
                            <td>
                                @if($supplier->balance > 0)
                                    <span class="badge bg-warning">مدين: {{ number_format($supplier->balance, 2) }} $</span>
                                @elseif($supplier->balance < 0)
                                    <span class="badge bg-success">دائن: {{ number_format(abs($supplier->balance), 2) }} $</span>
                                @else
                                    <span class="badge bg-info">متوازن</span>
                                @endif
                            </td>
                            <td onclick="event.stopPropagation();">
                                <a href="{{ route('warehouse.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">لا توجد بيانات للموردين حاليًا</td>
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
    // منع النقر على القائمة المنسدلة من تحويل الصف بأكمله
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
</script>
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

    .btn-sm {
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

    .bg-info {
        background-color: #17a2b8;
        color: #fff;
    }
</style>
