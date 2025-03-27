@extends('layouts.warehouse.app')
@section('title', 'الملف الشخصي - PharmaLink')
@section('content')

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
        <i class="fas fa-user-circle me-2"></i> {{ __('Profile') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- تحديث معلومات الملف الشخصي -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl animate__animated animate__fadeInUp">
            <div class="max-w-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <i class="fas fa-user-edit me-2"></i> تحديث معلومات الملف الشخصي
                </h3>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-user me-1"></i> الاسم
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-envelope me-1"></i> البريد الإلكتروني
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- تحديث كلمة المرور -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl animate__animated animate__fadeInUp">
            <div class="max-w-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <i class="fas fa-lock me-2"></i> تحديث كلمة المرور
                </h3>
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-key me-1"></i> كلمة المرور الحالية
                        </label>
                        <input id="current_password" name="current_password" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('current_password')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-key me-1"></i> كلمة المرور الجديدة
                        </label>
                        <input id="password" name="password" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('password')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-key me-1"></i> تأكيد كلمة المرور الجديدة
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> تحديث
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- حذف الحساب -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl animate__animated animate__fadeInUp">
            <div class="max-w-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <i class="fas fa-trash-alt me-2"></i> حذف الحساب
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">بمجرد حذف حسابك، سيتم مسح جميع بياناتك نهائيًا. هذا الإجراء لا يمكن التراجع عنه.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-6">
                    @csrf
                    @method('DELETE')
                    <div>
                        <label for="delete_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fas fa-key me-1"></i> كلمة المرور
                        </label>
                        <input id="delete_password" name="password" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('password')
                            <span class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف الحساب؟')">
                            <i class="fas fa-trash me-2"></i> حذف الحساب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* الأساسيات */
    body {
        background-color: #f5f7fa;
        font-family: 'Arial', sans-serif;
    }

    .dark body {
        background-color: #1f2937;
    }

    /* تحسينات البطاقات */
    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .shadow-lg:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -5px rgba(0, 0, 0, 0.1);
    }

    .sm\:rounded-xl {
        border-radius: 1rem;
    }

    /* العناوين الفرعية */
    h3 {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 8px;
    }

    .dark h3 {
        border-bottom-color: #4b5563;
    }

    /* تنسيق النماذج */
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        transition: all 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }

    /* الأزرار */
    .btn {
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #0288d1;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0277bd;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(2, 136, 209, 0.4);
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c62828;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }

    /* الأيقونات */
    .me-2 {
        margin-left: 0.5rem;
    }
</style>
@endsection
