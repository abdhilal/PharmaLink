@extends('layouts.app')

@section('title', 'التقرير المالي الشامل')

@section('content')
<div class="container mx-auto p-6">
    <!-- عنوان التقرير -->
    <div class="bg-indigo-500 text-white p-6 rounded-lg mb-6">
        <h1 class="text-2xl font-bold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            التقرير المالي الشامل
        </h1>
    </div>

    <!-- فلتر التاريخ -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('warehouse.reports.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                       class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg p-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                    تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- الإحصائيات الرئيسية -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- إجمالي المبيعات -->
        <div class="bg-green-500 text-white rounded-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">إجمالي المبيعات</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($totalSales, 2) }} ل.س</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- الإيرادات النقدية -->
        <div class="bg-cyan-500 text-white rounded-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">الإيرادات النقدية</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($totalOrders, 2) }} ل.س</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- إجمالي المصاريف -->
        <div class="bg-red-500 text-white rounded-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">إجمالي المصاريف</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($activePharmacies, 2) }} ل.س</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- صافي الربح -->
        <div class="bg-gray-600 text-white rounded-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">صافي الربح</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format(0, 2) }} ل.س</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- ديون الصيدليات والموردين -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- ديون الصيدليات -->
        <div class="bg-amber-500 text-white rounded-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-bold">ديون الصيدليات</h3>
                    </div>
                    <p class="text-3xl font-bold">{{ number_format(0, 2) }} ل.س</p>
                </div>
            </div>
        </div>

        <!-- ديون الموردين -->
        <div class="bg-indigo-500 text-white rounded-lg p-6">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="text-lg font-bold">ديون الموردين</h3>
                    </div>
                    <p class="text-3xl font-bold">{{ number_format(3947.15, 2) }} ل.س</p>
                </div>
            </div>
        </div>
    </div>

    <!-- تفاصيل المصاريف -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h2 class="text-xl font-bold text-gray-800">تفاصيل المصاريف</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-right py-3 px-4 bg-gray-50 text-gray-600">المبلغ</th>
                        <th class="text-right py-3 px-4 bg-gray-50 text-gray-600">الفئة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="py-3 px-4 text-gray-800">{{ number_format(0, 2) }} ل.س</td>
                        <td class="py-3 px-4 text-gray-800">مصاريف عامة</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
