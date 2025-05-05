@extends('layouts.warehouse.app')
@section('title', 'المدفوعات')
@section('content')
    <div class="content">
        <h1>المدفوعات</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>الصيدلية</th>
                    <th>الرصيد المستحق</th>
                    <th>إجمالي الديون</th>
                    <th>إجمالي المدفوعات</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td>{{ $account->pharmacy->name }}</td>
                        <td>
                            @php
                                $balance = $account->balance;
                                $status = $balance > 0 ? 'مستحق' : ($balance < 0 ? 'دائن' : 'متساوٍ');
                            @endphp
                            {{ number_format(abs($balance), 2) }} $ ({{ $status }})
                        </td>
                        <td>{{ number_format($account->transactions->where('type', 'debt')->sum('amount'), 2) }} $</td>
                        <td>{{ number_format($account->transactions->where('type', 'payment')->sum('amount'), 2) }} $</td>
                        <td>
                            <form action="{{ route('warehouse.payments.make') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="account_id" value="{{ $account->id }}">
                                <input type="number" name="amount" step="0.01" min="0.01" required placeholder="المبلغ">
                                <button type="submit" class="btn btn-success" onclick="return confirm('تسجيل دفعة؟')">تسجيل دفعة</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">لا توجد حسابات</td>
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
    .btn { padding: 5px 10px; color: white; border-radius: 5px; }
    .btn-success { background-color: #28a745; }
    .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
    input[type="number"] { width: 100px; padding: 5px; }
</style>
