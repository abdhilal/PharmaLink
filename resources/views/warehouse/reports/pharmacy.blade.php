@extends('layouts.app')

@section('title', "تقرير صيدلية {$pharmacy->name}")

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- معلومات الصيدلية -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $pharmacy->name }}</h1>
                    <p class="text-gray-600">{{ $pharmacy->address }}</p>
                    <p class="text-gray-600">{{ $pharmacy->phone }}</p>
                </div>
                <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 no-print">
                    طباعة التقرير
                </button>
            </div>

            <!-- فلاتر التقرير -->
            <form action="{{ route('warehouse.reports.pharmacy', $pharmacy) }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 no-print">
                <div>
                    <label class="block text-sm font-medium mb-2">من تاريخ</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">إلى تاريخ</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border rounded-lg p-2">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        تطبيق الفلتر
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">إجمالي المشتريات</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalSpent, 2) }} ل.س</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">عدد الطلبات</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($orderCount) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">متوسط قيمة الطلب</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($averageOrderValue, 2) }} ل.س</p>
        </div>
    </div>

    <!-- الأدوية الأكثر طلباً -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">الأدوية الأكثر طلباً</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-right py-3 px-4 bg-gray-50">اسم الدواء</th>
                            <th class="text-right py-3 px-4 bg-gray-50">الكمية المطلوبة</th>
                            <th class="text-right py-3 px-4 bg-gray-50">عدد مرات الطلب</th>
                            <th class="text-right py-3 px-4 bg-gray-50">إجمالي القيمة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mostOrderedMedicines as $medicine)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $medicine->name }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->total_quantity) }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->order_count) }}</td>
                            <td class="py-3 px-4">{{ number_format($medicine->total_quantity * $medicine->price, 2) }} ل.س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">تحليل المشتريات</h2>
                <canvas id="purchaseAnalysisChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">توزيع الطلبات</h2>
                <canvas id="orderDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- سجل الطلبات -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">سجل الطلبات</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-right py-3 px-4 bg-gray-50">رقم الطلب</th>
                            <th class="text-right py-3 px-4 bg-gray-50">التاريخ</th>
                            <th class="text-right py-3 px-4 bg-gray-50">عدد الأصناف</th>
                            <th class="text-right py-3 px-4 bg-gray-50">الإجمالي</th>
                            <th class="text-right py-3 px-4 bg-gray-50">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <a href="{{ route('warehouse.orders.show', $order) }}" class="text-blue-600 hover:underline">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-3 px-4">{{ $order->items_count }}</td>
                            <td class="py-3 px-4">{{ number_format($order->total, 2) }} ل.س</td>
                            <td class="py-3 px-4">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">قيد المعالجة</span>
                                        @break
                                    @case('processing')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">جاري التجهيز</span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">مكتمل</span>
                                        @break
                                    @case('cancelled')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">ملغي</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحليل المشتريات
    const purchaseCtx = document.getElementById('purchaseAnalysisChart').getContext('2d');
    const orders = @json($orders);
    
    const monthlyData = orders.reduce((acc, order) => {
        const date = new Date(order.created_at);
        const key = `${date.getFullYear()}-${date.getMonth() + 1}`;
        if (!acc[key]) {
            acc[key] = {
                total: 0,
                count: 0
            };
        }
        acc[key].total += order.total;
        acc[key].count += 1;
        return acc;
    }, {});

    const monthNames = ['يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو', 
                       'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

    const labels = Object.keys(monthlyData).map(key => {
        const [year, month] = key.split('-');
        return `${monthNames[parseInt(month) - 1]} ${year}`;
    });

    new Chart(purchaseCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'إجمالي المشتريات (ل.س)',
                    data: Object.values(monthlyData).map(d => d.total),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true
                },
                {
                    label: 'عدد الطلبات',
                    data: Object.values(monthlyData).map(d => d.count),
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.1)',
                    fill: true,
                    yAxisID: 'orders'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'المشتريات (ل.س)'
                    }
                },
                orders: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'عدد الطلبات'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // توزيع الطلبات
    const distributionCtx = document.getElementById('orderDistributionChart').getContext('2d');
    const statusCounts = orders.reduce((acc, order) => {
        if (!acc[order.status]) {
            acc[order.status] = 0;
        }
        acc[order.status]++;
        return acc;
    }, {});

    const statusLabels = {
        pending: 'قيد المعالجة',
        processing: 'جاري التجهيز',
        completed: 'مكتمل',
        cancelled: 'ملغي'
    };

    const statusColors = {
        pending: '#EAB308',
        processing: '#2563EB',
        completed: '#059669',
        cancelled: '#DC2626'
    };

    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusCounts).map(status => statusLabels[status]),
            datasets: [{
                data: Object.values(statusCounts),
                backgroundColor: Object.keys(statusCounts).map(status => statusColors[status]),
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
