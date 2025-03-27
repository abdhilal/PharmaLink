@extends('layouts.pharmacy.app')
@section('title', 'رصيد الحساب')
@section('content')
<div class="content">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- عنوان الصفحة -->
        <h1 class="mb-4 text-center animate__animated animate__fadeIn">
            <i class="fas fa-wallet me-2"></i> رصيد الحساب
        </h1>

        <!-- جدول الحسابات -->
        <div class="card shadow-sm border-0 animate__animated animate__fadeInUp">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i> تفاصيل الحسابات</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>المستودع</th>
                                <th>الرصيد المستحق</th>
                                <th>إجمالي الديون</th>
                                <th>إجمالي المدفوعات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                                <tr>
                                    <td>{{ $account->warehouse->user->name }}</td>
                                    <td>
                                        @php
                                            $balance = $account->balance;
                                            $status = $balance > 0 ? 'مستحق' : ($balance < 0 ? 'دائن' : 'متساوٍ');
                                            $color = $balance > 0 ? '#e74c3c' : ($balance < 0 ? '#28a745' : '#6c757d');
                                        @endphp
                                        <span style="color: {{ $color }};">
                                            {{ number_format(abs($balance), 2) }} $ ({{ $status }})
                                        </span>
                                    </td>
                                    <td>{{ number_format($account->transactions->where('type', 'debt')->sum('amount'), 2) }} $</td>
                                    <td>{{ number_format($account->transactions->where('type', 'payment')->sum('amount'), 2) }} $</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-exclamation-circle me-2"></i> لا توجد حسابات متاحة
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

    /* تنسيق النصوص */
    .text-muted {
        color: #6c757d;
    }

    .fw-bold {
        font-weight: 600;
    }
</style>
@endsection
