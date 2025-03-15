@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="content">
        <h1>Your Orders</h1>
        @foreach($orders as $order)
            <div class="order">
                <h2>Order #{{ $order->id }} - {{ $order->status }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->medicine->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->subtotal, 2) }} SAR</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p><strong>Total:</strong> {{ number_format($order->total_price, 2) }} SAR</p>
                @if(auth()->user()->role === 'warehouse')
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        <button type="submit" class="btn">Update Status</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endsection