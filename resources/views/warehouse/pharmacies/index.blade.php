@extends('layouts.warehouse.app')
@section('title', 'الصيدليات - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-store-alt me-2"></i> الصيدليات</h5>
    </div>
    <p></p>
    <div class="card-body">
        <!-- نموذج البحث -->
        <form method="GET" action="{{ route('warehouse.pharmacies.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو المدينة" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i> بحث</button>
            </div>
        </form>

        <!-- جدول الصيدليات -->
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الصيدلية</th>
                        <th>المدينة</th>
                        <th>عدد الطلبيات</th>
                        <th>الدين المستحق</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pharmacies as $index => $pharmacy)
                        <tr>
                            <td>{{ $pharmacies->firstItem() + $index }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td>{{ $pharmacy->city->name ?? 'غير محدد' }}</td>
                            <td>{{ $pharmacy->orders_count }}</td>
                            <td>
                                <span class="badge {{ ($pharmacy->accounts->first()->balance ?? 0) > 0 ? 'bg-danger' : 'bg-success' }}">
                                    {{ number_format($pharmacy->accounts->first()->balance ?? 0, 2) }} $
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('warehouse.pharmacies.show', $pharmacy->id) }}" class="btn btn-sm btn-info" title="تفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد صيدليات مرتبطة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- التصفح -->
        <div class="mt-4">
            {{ $pharmacies->links() }}
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
    .input-group .form-control {
        border-radius: 8px 0 0 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .input-group .btn {
        border-radius: 0 8px 8px 0;
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

    .bg-success {
        background-color: #28a745;
        color: #fff;
    }

    .bg-danger {
        background-color: #dc3545;
        color: #fff;
    }
</style>
