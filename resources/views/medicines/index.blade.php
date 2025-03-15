@extends('layouts.app')

@section('title', 'Browse Medicines')

@section('content')
    <div class="content">
        <h1>Medicines in Warehouse #{{ $warehouseId }}</h1>
        <form action="{{ route('cart.addMultiple') }}" method="POST">
            @csrf
            <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">
            @foreach($medicines as $company => $companyMedicines)
                <h2>{{ $company }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity Available</th>
                            <th>Offer</th>
                            <th>Quantity to Add</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companyMedicines as $medicine)
                            <tr>
                                <td>
                                    <input type="checkbox" name="items[{{ $medicine->id }}][medicine_id]" value="{{ $medicine->id }}">
                                </td>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ number_format($medicine->price, 2) }} SAR</td>
                                <td>{{ $medicine->quantity }}</td>
                                <td>{{ $medicine->offer ?? 'None' }}</td>
                                <td>
                                    <input type="number" name="items[{{ $medicine->id }}][quantity]" min="1" max="{{ $medicine->quantity }}" value="0" style="width: 60px;" disabled>
                                    <script>
                                        document.querySelector('input[name="items[{{ $medicine->id }}][medicine_id]"]').addEventListener('change', function() {
                                            document.querySelector('input[name="items[{{ $medicine->id }}][quantity]"]').disabled = !this.checked;
                                        });
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
            <button type="submit" class="btn">Add Selected to Cart</button>
        </form>
    </div>
@endsection