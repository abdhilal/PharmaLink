@extends('layouts.warehouse.app')

@section('title', 'إدارة المندوبين')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-users me-2"></i> إدارة المندوبين</h5>
            <a href="{{ route('warehouse.staff.create') }}" class="btn btn-success btn-sm">
                <i class="bx bx-plus me-1"></i> إضافة مندوب جديد
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('warehouse.staff.edit', $employee) }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-edit me-1"></i> تعديل
                                    </a>
                                    <form action="{{ route('warehouse.staff.destroy', $employee) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                            <i class="bx bx-trash me-1"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">لا يوجد موظفون بعد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
