@extends('layouts.warehouse.app')
@section('title', 'تعديل موظف - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">تعديل موظف: {{ $employee->name }}</h5>
    <div class="card-body">
        <form action="{{ route('warehouse.employees.update', $employee->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control" value="{{ $employee->phone }}">
            </div>

            <div class="mb-3">
                <label class="form-label">المنصب</label>
                <input type="text" name="position" class="form-control" value="{{ $employee->position }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الراتب</label>
                <input type="number" name="salary" class="form-control" step="0.01" value="{{ $employee->salary }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ التوظيف</label>
                <input type="date" name="date" class="form-control" value="{{ $employee->date }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">تحديث</button>
        </form>
    </div>
</div>
@endsection
