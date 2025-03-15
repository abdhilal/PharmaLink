@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="content">
        <h1>Your Accounts</h1>
        @foreach($accounts as $account)
            <div class="account">
                <h2>Account with {{ $account->warehouse->address }}</h2>
                <p><strong>Balance:</strong> {{ number_format($account->balance, 2) }} SAR</p>
                <h3>Transactions</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($account->transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->order_id ?? 'N/A' }}</td>
                                <td>{{ number_format($transaction->amount, 2) }} SAR</td>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td>{{ $transaction->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(auth()->user()->role === 'pharmacy')
                    <form action="{{ route('payment.make') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $account->transactions->first()->order_id }}">
                        <input type="number" name="amount" step="0.01" min="0.01" placeholder="Amount to pay" style="padding: 8px;">
                        <button type="submit" class="btn">Make Payment</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endsection