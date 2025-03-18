@extends('layouts.app')
@section('title', 'تعديل الطلبية')
@section('content')
    <div class="content">
        <h1>تعديل الطلبية #{{ $order->id }}</h1>
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم الدواء</th>
                        <th>الكمية</th>
                        <th>السعر للوحدة</th>
                        <th>المجموع الفرعي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->medicine->name }}</td>
                            <td>
                                <input type="number" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}" min="1" required>
                                <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                            </td>
                            <td>{{ number_format($item->price_per_unit, 2) }} ريال</td>
                            <td>{{ number_format($item->subtotal, 2) }} ريال</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </form>
    </div>
@endsection

<style>
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px; }
</style>
