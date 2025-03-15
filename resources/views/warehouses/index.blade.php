@extends('layouts.app')

@section('title', 'Select Warehouse')

@section('content')
    <div class="content">
        <h1>Select a Warehouse</h1>
        <p>Choose a warehouse to browse its medicines:</p>
        <table>
            <thead>
                <tr>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warehouses as $warehouse)
                    <tr>
                        <td>{{ $warehouse->address }}</td>
                        <td>{{ $warehouse->phone }}</td>
                        <td>
                            <a href="{{ route('medicines.index', ['warehouse' => $warehouse->id]) }}" class="btn">Browse Medicines</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No warehouses available in your city.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection