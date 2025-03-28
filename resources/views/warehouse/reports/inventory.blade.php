@extends('layouts.app')

@section('title', 'تقرير المخزون')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- إحصائيات المخزون -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">إجمالي الأدوية</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($medicines->count()) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">الأدوية منخفضة المخزون</h3>
            <p class="text-3xl font-bold text-yellow-600">
                {{ number_format($medicines->where('quantity', '<=', 'min_quantity')->count()) }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">الأدوية النافذة</h3>
            <p class="text-3xl font-bold text-red-600">
                {{ number_format($medicines->where('quantity', '<=', 0)->count()) }}
            </p>
        </div>
    </div>

    <!-- جدول المخزون -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">تقرير المخزون</h2>
                <div class="flex gap-4">
                    <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        طباعة التقرير
                    </button>
                    <a href="{{ route('warehouse.medicines.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        إضافة دواء جديد
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-right py-3 px-4 bg-gray-50">اسم الدواء</th>
                            <th class="text-right py-3 px-4 bg-gray-50">الكمية المتوفرة</th>
                            <th class="text-right py-3 px-4 bg-gray-50">الحد الأدنى</th>
                            <th class="text-right py-3 px-4 bg-gray-50">المبيعات الشهرية</th>
                            <th class="text-right py-3 px-4 bg-gray-50">إجمالي المبيعات</th>
                            <th class="text-right py-3 px-4 bg-gray-50">السعر</th>
                            <th class="text-right py-3 px-4 bg-gray-50">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $medicine)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <a href="{{ route('warehouse.medicines.show', $medicine) }}" class="text-blue-600 hover:underline">
                                    {{ $medicine->name }}
                                </a>
                            </td>
                            <td class="py-3 px-4">{{ number_format($medicine->quantity) }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->min_quantity) }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->monthly_sales) }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->total_sold) }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->price, 2) }} ل.س</td>
                            <td class="py-3 px-4">
                                @if($medicine->quantity <= 0)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">نفذت الكمية</span>
                                @elseif($medicine->quantity <= $medicine->min_quantity)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">منخفض</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">متوفر</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- الرسم البياني -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">توزيع المخزون</h2>
                <canvas id="inventoryDistributionChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">المبيعات الشهرية للأدوية الأكثر طلباً</h2>
                <canvas id="topSellingChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // توزيع المخزون
    const distributionCtx = document.getElementById('inventoryDistributionChart').getContext('2d');
    const medicines = @json($medicines);
    
    const outOfStock = medicines.filter(m => m.quantity <= 0).length;
    const lowStock = medicines.filter(m => m.quantity > 0 && m.quantity <= m.min_quantity).length;
    const inStock = medicines.filter(m => m.quantity > m.min_quantity).length;

    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['متوفر', 'منخفض', 'نافذ'],
            datasets: [{
                data: [inStock, lowStock, outOfStock],
                backgroundColor: ['#059669', '#EAB308', '#DC2626'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // المبيعات الشهرية للأدوية الأكثر طلباً
    const topSellingCtx = document.getElementById('topSellingChart').getContext('2d');
    const topMedicines = medicines
        .sort((a, b) => b.monthly_sales - a.monthly_sales)
        .slice(0, 5);

    new Chart(topSellingCtx, {
        type: 'bar',
        data: {
            labels: topMedicines.map(m => m.name),
            datasets: [{
                label: 'المبيعات الشهرية',
                data: topMedicines.map(m => m.monthly_sales),
                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                borderColor: '#2563eb',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<style>
@media print {
    nav, button, .no-print {
        display: none !important;
    }
    body {
        background: white;
    }
    .container {
        max-width: 100% !important;
        padding: 0 !important;
    }
    .shadow {
        box-shadow: none !important;
    }
}
</style>
@endpush
