@extends('layouts.app')
@section('title', 'تعديل الدواء')
@section('content')
    <div class="content">
        <h1>تعديل الدواء: {{ $medicine->name }}</h1>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('warehouse.medicines.update', $medicine) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">اسم الدواء:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $medicine->name) }}" required>
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="company_id">الشركة:</label>
                <select name="company_id" id="company_id" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $company->id == $medicine->company_id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="price">السعر (ريال):</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $medicine->price) }}" required>
                @error('price') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="quantity">الكمية المتاحة:</label>
                <input type="number" name="quantity" id="quantity" min="0" value="{{ old('quantity', $medicine->quantity) }}" required>
                @error('quantity') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="offer">العرض (اختياري):</label>
                <input type="text" name="offer" id="offer" value="{{ old('offer', $medicine->offer) }}">
                @error('offer') <span class="error">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </form>
    </div>
@endsection

<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input, select { width: 100%; padding: 8px; }
    .error { color: red; font-size: 0.9em; }
    .btn { padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px; }
    .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
</style>
