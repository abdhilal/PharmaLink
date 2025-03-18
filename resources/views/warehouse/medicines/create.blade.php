@extends('layouts.app') <!-- استخدام القالب الأساسي -->

@section('title', 'إضافة دواء جديد') <!-- عنوان الصفحة -->

@section('content')
    <div class="content">
        <!-- عنوان رئيسي للصفحة -->
        <h1>إضافة دواء جديد</h1>

        <!-- نموذج لإدخال بيانات الدواء -->
        <form action="{{ route('warehouse.medicines.store') }}" method="POST">
            @csrf <!-- رمز CSRF للحماية -->

            <!-- حقل اسم الدواء -->
            <div class="form-group">
                <label for="name">اسم الدواء:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name') <span class="error">{{ $message }}</span> @enderror <!-- رسالة خطأ إن وجدت -->
            </div>

            <!-- حقل اختيار الشركة -->
            <div class="form-group">
                <label for="company_id">الشركة:</label>
                <select name="company_id" id="company_id" required>
                    @foreach($companies as $company) <!-- حلقة لعرض الشركات -->
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id') <span class="error">{{ $message }}</span> @enderror
            </div>

            <!-- حقل السعر -->
            <div class="form-group">
                <label for="price">السعر (ريال):</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price') }}" required>
                @error('price') <span class="error">{{ $message }}</span> @enderror
            </div>

            <!-- حقل الكمية -->
            <div class="form-group">
                <label for="quantity">الكمية المتاحة:</label>
                <input type="number" name="quantity" id="quantity" min="0" value="{{ old('quantity') }}" required>
                @error('quantity') <span class="error">{{ $message }}</span> @enderror
            </div>

            <!-- حقل العرض (اختياري) -->
            <div class="form-group">
                <label for="offer">العرض (اختياري):</label>
                <input type="text" name="offer" id="offer" value="{{ old('offer') }}">
                @error('offer') <span class="error">{{ $message }}</span> @enderror
            </div>

            <!-- زر الإرسال -->
            <button type="submit" class="btn">إضافة الدواء</button>
        </form>
    </div>
@endsection

<style>
    /* تنسيق بسيط لتحسين المظهر */
    .content { padding: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input, select { width: 100%; max-width: 300px; padding: 8px; }
    .error { color: red; font-size: 0.9em; }
    .btn { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .btn:hover { background-color: #0056b3; }
</style>
