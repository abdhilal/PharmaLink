@extends('layouts.warehouse.app') <!-- سننشئ تخطيطًا بسيطًا لاحقًا -->

@section('title', 'الطلبيات الجاهزة')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bx bx-package me-2"></i> الطلبيات الجاهزة</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الطلبية</th>
                            <th>اسم الصيدلية</th>
                            <th>المدينة</th>
                            <th>الحالة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->pharmacy->name }}</td>
                                <td>{{ $order->pharmacy->city->name }}</td>
                                <td><span class="badge bg-warning">جاهزة</span></td>
                                <td>
                                    <form action="{{ route('staff.orders.deliver', $order) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bx bx-check me-1"></i> تسليم
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">لا توجد طلبيات جاهزة حاليًا</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
