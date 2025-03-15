<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaLink - @yield('title')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        nav { background: #2d71f4; padding: 10px; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        .content { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .btn { background: #2d71f4; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #235ab9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <nav>
        <div class="container">
            <a href="{{ route('home') }}">PharmaLink</a>
            @auth
                <a href="{{ route('medicines.index') }}">Medicines</a>
                <a href="{{ route('cart.show') }}">Cart</a>
                <a href="{{ route('orders.index') }}">Orders</a>
                <a href="{{ route('payments.index') }}">Payments</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>