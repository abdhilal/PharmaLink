
<div class="cart-container">
    <h1>Your Cart</h1>
    @foreach($invoices as $warehouseId => $invoice)
        <div class="invoice">
            <h2>Warehouse #{{ $warehouseId }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice['items'] as $item)
                        <tr>
                            <td>{{ $item['medicine']->name }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['subtotal'], 2) }} SAR</td>
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
            <button type="submit" class="checkout-btn">Checkout</button>
        </form>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>

<style>
    .cart-container { max-width: 800px; margin: 2rem auto; padding: 2rem; background: #fff; border-radius: 12px; }
    .invoice { margin-bottom: 2rem; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
    th, td { padding: 0.75rem; border: 1px solid #ddd; }
    .checkout-btn { background-color: #2d71f4; color: #fff; padding: 0.75rem 1.5rem; border-radius: 30px; border: none; cursor: pointer; }
    .checkout-btn:hover { background-color: #235ab9; }
</style>
