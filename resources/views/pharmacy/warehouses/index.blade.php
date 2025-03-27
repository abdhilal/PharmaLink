@extends('layouts.pharmacy.app')

@section('title', 'المستودعات')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
            <!-- الخدمة العاجلة -->
            <div class="urgent-service bg-warning-subtle p-3 mb-4 rounded d-flex justify-content-between align-items-center">
                <div>
                    <i class="bx bx-rocket me-2 text-warning" style="font-size: 1.5rem;"></i>
                    <span class="fw-bold">خدمة الطلب العاجل</span>
                </div>
                <a href="{{ route('pharmacy.urgentorder.create') }}" class="btn btn-warning btn-sm">
                    <i class="bx bx-plus me-1"></i> إنشاء طلبية عاجلة
                </a>
            </div>

            <!-- رأس البطاقة -->
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="bx bx-warehouse me-2"></i> اختر مستودعًا
                </h5>
            </div>

            <!-- جسم البطاقة -->
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="bx bx-info-circle me-2"></i> اختر مستودعًا لتصفح الأدوية المتوفرة فيه:
                </p>
                <div class="table-responsive">
                    <table class="table table-modern table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>الاسم</th>
                                <th>العنوان</th>
                                <th>يبعد عنك تقريبا </th>
                                <th>رقم الهاتف</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->name ?? 'غير محدد' }}</td>
                                    <td>{{ $warehouse->city->name ?? 'غير محدد' }}</td>
                                    <td>(كم) {{ $warehouse->city->distance ?? 'غير محدد' }} </td>
                                    <td>{{ $warehouse->phone ?? 'غير متوفر' }}</td>
                                    <td>
                                        <a href="{{ route('pharmacy.warehouses.show', $warehouse->warehouse->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bx bx-eye me-1"></i> تصفح الأدوية
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        لا توجد مستودعات متاحة في مدينتك.
                                        <p class="mt-2">حدد موقعك للحصول على المستودعات:</p>
                                        <a href="{{ route('pharmacy.settings.cities') }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="bx bx-location-plus me-1"></i> احصل على موقعي
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* الأساسيات */
    .content-wrapper {
        background-color: #f5f7fa;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        transition: box-shadow 0.3s ease;
    }

    .shadow-sm {
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

    /* الخدمة العاجلة */
    .urgent-service {
        background-color: #fff3cd;
        border: 1px solid #ffe69c;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .urgent-service:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #212529;
    }

    /* النص التوضيحي */
    .text-muted {
        font-size: 1rem;
        color: #718096;
    }

    /* الجدول */
    .table-modern th {
        background-color: #f8fafc;
        color: #4a5568;
        font-weight: 600;
        padding: 1rem;
        text-align: center;
    }

    .table-modern td {
        padding: 1rem;
        text-align: center;
        background-color: #fff;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-modern tbody tr:hover td {
        background-color: rgba(59, 130, 246, 0.05);
        transition: background-color 0.3s ease;
    }

    /* الأزرار */
    .btn-primary {
        background-color: #3B82F6;
        border-color: #3B82F6;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2563EB;
        border-color: #2563EB;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.9rem;
    }

    .btn i {
        font-size: 1rem;
    }
</style>
@endsection
