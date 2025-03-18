@extends('layouts.app')
@section('title', 'رصيد الحساب')
@section('content')
    <div class="content">
        <h1>رصيد الحساب</h1>
        <table class="table">
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
                        <td>{{ $account->warehouse->name }}</td>
                        <td>
                            @php
                                $balance = $account->balance;
                                $status = $balance > 0 ? 'مستحق' : ($balance < 0 ? 'دائن' : 'متساوٍ');
                            @endphp
                            {{ number_format(abs($balance), 2) }} ريال ({{ $status }})
                        </td>
                        <td>{{ number_format($account->transactions->where('type', 'debt')->sum('amount'), 2) }} ريال</td>
                        <td>{{ number_format($account->transactions->where('type', 'payment')->sum('amount'), 2) }} ريال</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">لا توجد حسابات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<style>
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
</style>
