@extends('layouts.warehouse.app')
@section('title', 'تعديل دواء')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1>تعديل دواء: {{ $medicine->name }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('warehouse.medicines.update', $medicine) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>اسم الدواء</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $medicine->name) }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الشركة</label>
                            <select name="company_id" class="form-control" required>
                                <option value="">اختر الشركة</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $medicine->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>السعر</label>
                            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $medicine->price) }}" required>
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الكمية</label>
                            <input type="number" name="quantity" class="form-control" min="0" value="{{ old('quantity', $medicine->quantity) }}" required>
                            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>تاريخ انتهاء الصلاحية</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $medicine->date) }}" required>
                            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>الباركود (اختياري)</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $medicine->barcode) }}">
                            @error('barcode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>العرض (اختياري)</label>
                            <input type="text" name="offer" class="form-control" value="{{ old('offer', $medicine->offer) }}">
                            @error('offer') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">تحديث الدواء</button>
                            <a href="{{ route('warehouse.medicines.index') }}" class="btn btn-secondary">رجوع</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .form-control { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
    .btn { padding: 8px 16px; }
    .alert-success { padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; }
    .text-danger { color: #dc3545; }
</style>
