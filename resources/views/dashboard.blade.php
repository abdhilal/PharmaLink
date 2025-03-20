@extends('layouts.warehouse.app')
@section('title', 'لوحة التحكم')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- إحصائيات سريعة -->
            <div class="col-xxl-4 col-lg-12 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <a href="{{ route('orders.index') }}" class="menu-link">

                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('warehouse/img/icons/unicons/chart-success.png') }}"
                                            alt="Orders" class="rounded" />
                                    </div>
                                </div>
                                <p class="mb-1">الطلبيات المسلمة</p>
                                <h4 class="card-title mb-3">{{ $deliveredOrders }}</h4>
                                <small class="text-success fw-medium">إجمالي المبيعات: {{ number_format($totalSales, 2) }} ريال</small>
                            </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <a href="{{ route('pharmacies.index') }}" class="menu-link">

                            <div class="card-body">

                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('warehouse/img/icons/unicons/wallet-info.png') }}"
                                            alt="Pharmacies" class="rounded" />
                                    </div>
                                </div>
                                <p class="mb-1">الصيدليات المرتبطة</p>
                                <h4 class="card-title mb-3">{{ $pharmaciesCount }}</h4>
                                <small class="fw-medium">إجمالي الديون: {{ number_format($totalDebt, 2) }} ريال</small>
                            </div>
                        </a>

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('warehouse/img/icons/unicons/wallet-info.png') }}"
                                            alt="Pharmacies" class="rounded" />
                                    </div>
                                </div>
                                <p class="mb-1">اجمالي الديون</p>
                                <h4 class="card-title mb-3">{{ $totalDebt }}</h4>
                                <small class="fw-medium">إجمالي الديون: {{ number_format($totalDebt, 2) }} ريال</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('warehouse/img/icons/unicons/wallet-info.png') }}"
                                            alt="Capital" class="rounded" />
                                    </div>
                                </div>
                                <p class="mb-1">إجمالي رأس المال</p>
                                <h4 class="card-title mb-3">{{ number_format($capital, 2) }} ريال</h4>
                                <small class="fw-medium">القيمة الإجمالية للمخزون</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الأدوية منخفضة الكمية -->
            <div class="col-md-6 col-lg-4 order-0 mb-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">الأدوية منخفضة الكمية</h5>
                    </div>
                    <div class="card-body pt-4">
                        <ul class="p-0 m-0">
                            @forelse($lowStockMedicines as $medicine)
                                <a href="{{route('warehouse.medicines.edit',$medicine->id)}}"><li class="d-flex align-items-center mb-6">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-danger">
                                            <i class="icon-base bx bx-error"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <small class="d-block">{{ $medicine->name }}</small>
                                            <h6 class="fw-normal mb-0">{{ $medicine->company->name }}</h6>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-2">
                                            <h6 class="fw-normal mb-0">{{ $medicine->quantity }}</h6>
                                            <span class="text-muted">ريال {{ number_format($medicine->price, 2) }}</span>
                                        </div>
                                    </div>
                                </li>
                            </a>
                            @empty
                                <li class="text-center">لا توجد أدوية منخفضة الكمية</li>
                            @endforelse
                        </ul>
                        <a href="{{ route('warehouse.medicines.index') }}" class="btn btn-outline-primary mt-3">عرض جميع الأدوية</a>
                    </div>
                </div>
            </div>

            <!-- الأدوية التي ستنتهي -->
            <div class="col-md-6 col-lg-4 order-1 mb-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">الأدوية التي ستنتهي قريبًا</h5>
                    </div>
                    <div class="card-body pt-4">
                        <ul class="p-0 m-0">
                            @forelse($expiringMedicines as $medicine)
                            <a href="{{route('warehouse.medicines.edit',$medicine->id)}}">
                            <li class="d-flex align-items-center mb-6">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-warning">
                                            <i class="icon-base bx bx-time"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <small class="d-block">{{ $medicine->name }}</small>
                                            <h6 class="fw-normal mb-0">{{ $medicine->company->name }}</h6>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-2">
                                            <h6 class="fw-normal mb-0">{{ $medicine->date }}</h6>
                                            <span class="text-muted">الكمية: {{ $medicine->quantity }}</span>
                                        </div>
                                    </div>
                                </li>
                            </a>
                            @empty
                                <li class="text-center">لا توجد أدوية ستنتهي قريبًا</li>
                            @endforelse
                        </ul>
                        <a href="{{ route('warehouse.medicines.index') }}" class="btn btn-outline-primary mt-3">عرض جميع الأدوية</a>
                    </div>
                </div>
            </div>

            <!-- آخر الطلبيات -->
            <div class="col-md-6 col-lg-4 order-1 mb-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">آخر الطلبيات</h5>
                    </div>
                    <div class="card-body pt-4">
                        <ul class="p-0 m-0">
                            @forelse($latestOrders as $order)
                                <li class="d-flex align-items-center mb-6">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="icon-base bx bx-package"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <small class="d-block">طلبية #{{ $order->id }}</small>
                                            <h6 class="fw-normal mb-0">{{ $order->pharmacy->name }}</h6>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-2">
                                            <h6 class="fw-normal mb-0">{{ number_format($order->total_price, 2) }} ريال</h6>
                                            <span class="text-muted">{{ $order->status === 'pending' ? 'معلقة' : 'مسلمة' }}</span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center">لا توجد طلبيات حديثة</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-backdrop fade"></div>
</div>
@endsection
