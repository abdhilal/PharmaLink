@extends('layouts.warehouse.app')

@section('title', 'تعديل مندوب')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bx bx-edit me-2"></i> تعديل مندوب</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('warehouse.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمة المرور (اتركه فارغًا إذا لم ترغب في التغيير)</label>
                    <input type="password" name="password" class="form-control">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <!-- قسم الأذونات -->
                <div class="mb-3">
                    <label class="form-label">الأذونات</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->name }}"
                                           class="form-check-input"
                                           id="permission-{{ $permission->name }}"
                                           {{ $staff->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission-{{ $permission->name }}">
                                        {{ $permission->name == 'view-orders' ? 'عرض الطلبيات' : 'تسليم الطلبيات' }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-success">تحديث مندوب</button>
            </form>
        </div>
    </div>
</div>
@endsection
