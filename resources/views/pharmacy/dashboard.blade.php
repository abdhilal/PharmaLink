@extends('layouts.pharmacy.app')
@section('title', 'لوحة التحكم')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            <!-- الأدوية منخفضة الكمية -->
            <div class="col-md-6 col-lg-4 order-0">
                <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp">
                    <div class="card-header bg-gradient-danger text-white d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0"><i class="fas fa-exclamation-triangle me-2"></i> الأدوية منخفضة الكمية</h5>
                    </div>
                    <div class="card-body pt-4">
                        <ul class="p-0 m-0">
                                <a href="" class="text-decoration-none">
                                    <li class="d-flex align-items-center mb-3 p-2 rounded hover-bg">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-danger">
                                                <i class="fas fa-exclamation"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <small class="d-block text-muted">name</small>
                                                <h6 class="fw-normal mb-0">name</h6>
                                            </div>
                                            <div class="user-progress d-flex align-items-center gap-2">
                                                <h6 class="fw-normal mb-0 text-danger">numnber</h6>
                                                <span class="text-muted">$ </span>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <li class="text-center text-muted">لا توجد أدوية منخفضة الكمية</li>
                        </ul>
                        <a href="{{ route('warehouse.medicines.index') }}" class="btn btn-outline-primary mt-4 w-100">
                            <i class="fas fa-list me-2"></i> عرض جميع الأدوية
                        </a>
                    </div>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
                <div class="row g-4">
                    <div class="col-lg-6 col-md-12 col-6">
                        <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp">
                            <a href="{{ route('warehouse.orders.index') }}" class="text-decoration-none">
                                <div class="card-body text-center">
                                    <div class="avatar mx-auto mb-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="fas fa-shopping-cart"></i>
                                        </span>
                                    </div>
                                    <p class="mb-1">الطلبيات المسلمة</p>
                                    <h4 class="card-title mb-3">07</h4>
                                    <small class="text-success fw-medium">إجمالي المبيعات: $</small>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6">
                        <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp">
                            <a href="{{ route('warehouse.pharmacies.index') }}" class="text-decoration-none">
                                <div class="card-body text-center">
                                    <div class="avatar mx-auto mb-3">
                                        <span class="avatar-initial rounded bg-label-info">
                                            <i class="fas fa-store-alt"></i>
                                        </span>
                                    </div>
                                    <p class="mb-1">الصيدليات المرتبطة</p>
                                    <h4 class="card-title mb-3">hh</h4>
                                    <small class="fw-medium">إجمالي الديون:  $</small>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6">
                        <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                </div>
                                <p class="mb-1">إجمالي الديون</p>
                                <h4 class="card-title mb-3"> $</h4>
                                <small class="fw-medium">إجمالي الديون الكلية</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6">
                        <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="fas fa-coins"></i>
                                    </span>
                                </div>
                                <p class="mb-1">إجمالي رأس المال</p>
                                <h4 class="card-title mb-3"> $</h4>
                                <small class="fw-medium">القيمة الإجمالية للمخزون</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الأدوية التي ستنتهي -->
            <div class="col-md-6 col-lg-4 order-1">
                <div class="card h-100 shadow-sm border-0 animate__animated animate__fadeInUp">
                    <div class="card-header bg-gradient-warning text-dark d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0"><i class="fas fa-hourglass-end me-2"></i> الأدوية التي ستنتهي قريبًا</h5>
                    </div>
                    <div class="card-body pt-4">
                        <ul class="p-0 m-0">
                                <a href="" class="text-decoration-none">
                                    <li class="d-flex align-items-center mb-3 p-2 rounded hover-bg">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-warning">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <small class="d-block text-muted">name</small>
                                                <h6 class="fw-normal mb-0">name</h6>
                                            </div>
                                            <div class="user-progress d-flex align-items-center gap-2">
                                                <h6 class="fw-normal mb-0 text-warning">date</h6>
                                                <span class="text-muted">الكمية: </span>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                <li class="text-center text-muted">لا توجد أدوية ستنتهي قريبًا</li>
                        </ul>
                        <a href="" class="btn btn-outline-primary mt-4 w-100">
                            <i class="fas fa-list me-2"></i> عرض جميع الأدوية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-backdrop fade"></div>
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

    .bg-gradient-danger {
        background: linear-gradient(45deg, #d32f2f, #f44336);
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffca28, #ffeb3b);
    }

    /* الأدوية */
    .hover-bg:hover {
        background-color: #f8f9fa;
        transition: background-color 0.3s;
    }

    .avatar-initial {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-label-danger {
        background-color: #fee2e2;
        color: #dc3545;
    }

    .bg-label-warning {
        background-color: #fef3c7;
        color: #f59e0b;
    }

    .bg-label-success {
        background-color: #d1fae5;
        color: #28a745;
    }

    .bg-label-info {
        background-color: #cce5ff;
        color: #007bff;
    }

    .bg-label-primary {
        background-color: #cce5ff;
        color: #0288d1;
    }

    /* الإحصائيات */
    .card-body {
        padding: 20px;
    }

    .card-body p {
        font-size: 1rem;
        color: #343a40;
    }

    .card-body h4 {
        font-weight: bold;
        color: #212529;
    }

    .card-body small {
        font-size: 0.9rem;
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
</style>
