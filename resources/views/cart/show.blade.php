@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    <div class="content">
        <h1>Your Cart</h1>
        @foreach($invoices as $warehouseId => $invoice)
            <div class="invoice">
                <h2>Warehouse #{{ $warehouseId }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Price/Unit</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice['items'] as $item)
                            <tr>
                                <td>{{ $item['medicine']->name }}</td>
                                <td>{{ number_format($item['price_per_unit'], 2) }} SAR</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['subtotal'], 2) }} SAR</td>
                                <td>
                                    <form action="{{ route('cart.update') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="medicine_id" value="{{ $item['medicine']->id }}">
                                        <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['medicine']->quantity }}" style="width: 60px;">
                                        <button type="submit" class="btn">Update</button>
                                    </form>
                                    <form action="{{ route('cart.remove') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="medicine_id" value="{{ $item['medicine']->id }}">
                                        <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">
                                        <button type="submit" class="btn" style="background: #e74c3c;">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p><strong>Total:</strong> {{ number_format($invoice['total'], 2) }} SAR</p>
            </div>
        @endforeach
        @if(!empty($invoices))
            <form method="POST" action="{{ route('cart.checkout') }}">
                @csrf
                <button type="submit" class="btn">Checkout</button>
            </form>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
@endsection