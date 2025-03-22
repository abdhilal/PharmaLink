@extends('layouts.warehouse.app')
@section('title', 'صندوق المستودع - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">صندوق المستودع</h5>
    <div class="card-body">
        <h6>الرصيد الحالي: {{ number_format($currentBalance, 2) }} ريال</h6>
        <div class="table-responsive">
            <table class="table table-hover">
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
                        <td>{{ $transaction->transaction_type == 'income' ? 'دخل' : 'مصروف' }}</td>
                        <td>{{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->date }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">لا توجد حركات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
