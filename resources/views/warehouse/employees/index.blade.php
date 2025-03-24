@extends('layouts.warehouse.app')
@section('title', 'الموظفون - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>الموظفون</span>
        <a href="{{ route('warehouse.employees.create') }}" class="btn btn-primary">إضافة موظف</a>
    </h5>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>المنصب</th>
                    <th>الراتب</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $index => $employee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ number_format($employee->salary, 2) }} ريال</td>
                    <td>{{ $employee->status === 'active' ? 'نشط' : 'غير نشط' }}</td>
                    <td>
                        <a href="{{ route('warehouse.employees.show', $employee->id) }}" class="btn btn-sm btn-info">عرض</a>
                        <a href="{{ route('warehouse.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                        <form action="{{ route('warehouse.employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">لا يوجد موظفون</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
