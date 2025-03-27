@extends('layouts.warehouse.app')
@section('title', 'صندوق المستودع - PharmaLink')
@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="mb-0"><i class="fas fa-wallet me-2"></i> صندوق المستودع</h5>
    </div>
    <p></p>
    <div class="card-body">
        <!-- الرصيد الحالي -->
        <div class="balance-section mb-4">
            <h6 class="balance-title"><i class="fas fa-money-bill-wave me-2"></i> الرصيد الحالي:
                <span class="balance-amount">{{ number_format($currentBalance, 2) }} $</span>
            </h6>
        </div>

        <!-- جدول الحركات -->
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge {{ $transaction->transaction_type == 'income' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $transaction->transaction_type == 'income' ? 'دخل' : 'مصروف' }}
                                </span>
                            </td>
                            <td>{{ number_format($transaction->amount, 2) }} $</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">لا توجد حركات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

    /* الرصيد الحالي */
    .balance-section {
        background: #e9ecef;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
    }

    .balance-title {
        font-size: 1.5rem;
        color: #343a40;
        margin: 0;
    }

    .balance-amount {
        font-weight: bold;
        color: #28a745;
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
