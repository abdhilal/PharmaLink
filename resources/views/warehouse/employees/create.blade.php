@extends('layouts.warehouse.app')
@section('title', 'إضافة موظف - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">إضافة موظف جديد</h5>
    <div class="card-body">
        <form action="{{ route('warehouse.employees.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">المنصب</label>
                <input type="text" name="position" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الراتب</label>
                <input type="number" name="salary" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">تاريخ التوظيف</label>
                <input type="date" name="date" class="form-control" value="{{now()}}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select" required>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">إضافة</button>
        </form>
    </div>
</div>
@endsection
