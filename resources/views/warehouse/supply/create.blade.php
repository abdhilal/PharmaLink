@extends('layouts.warehouse.app')

@section('title', 'إضافة مورد جديد - PharmaLink')

@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>إضافة مورد جديد</span>
        <a href="{{ route('warehouse.suppliers.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> رجوع إلى القائمة
        </a>
    </h5>
    <div class="card-body">
        <form action="{{ route('warehouse.suppliers.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- اسم المورد -->
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">اسم المورد <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- رقم الهاتف -->
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- العنوان -->
                <div class="col-md-12 mb-3">
                    <label for="address" class="form-label">العنوان</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- أزرار الإرسال والإلغاء -->
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bx bx-plus-circle me-1"></i> إضافة المورد
                </button>
                <a href="{{ route('warehouse.suppliers.index') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-x me-1"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // يمكن إضافة جافا سكريبت لاحقًا إذا أردت تحسين التفاعل
</script>
@endsection
