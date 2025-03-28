@extends('layouts.app')

@section('title', 'تقرير المبيعات')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- فلاتر التقرير -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">فلاتر التقرير</h2>
            <form action="{{ route('warehouse.reports.sales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">الفترة</label>
                    <select name="period" class="w-full border rounded-lg p-2">
                        <option value="daily" @selected($period === 'daily')>يومي</option>
                        <option value="monthly" @selected($period === 'monthly')>شهري</option>
                        <option value="yearly" @selected($period === 'yearly')>سنوي</option>
                    </select>
                </div>
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

    <!-- الرسم البياني -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">تحليل المبيعات</h2>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- جدول البيانات -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">تفاصيل المبيعات</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-right py-3 px-4 bg-gray-50">الفترة</th>
                            <th class="text-right py-3 px-4 bg-gray-50">عدد الطلبات</th>
                            <th class="text-right py-3 px-4 bg-gray-50">إجمالي المبيعات</th>
                            <th class="text-right py-3 px-4 bg-gray-50">متوسط قيمة الطلب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                @if(isset($sale->date))
                                    {{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d') }}
                                @elseif(isset($sale->month))
                                    {{ \Carbon\Carbon::createFromDate($sale->year, $sale->month, 1)->format('F Y') }}
                                @else
                                    {{ $sale->year }}
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ number_format($sale->order_count) }}</td>
                            <td class="py-3 px-4">{{ number_format($sale->total_sales, 2) }} ل.س</td>
                            <td class="py-3 px-4">{{ number_format($sale->total_sales / $sale->order_count, 2) }} ل.س</td>
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
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($sales);
    
    const labels = salesData.map(sale => {
        if (sale.date) {
            return sale.date;
        } else if (sale.month) {
            const monthNames = ['يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو', 
                              'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            return monthNames[sale.month - 1] + ' ' + sale.year;
        } else {
            return sale.year.toString();
        }
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'المبيعات (ل.س)',
                    data: salesData.map(sale => sale.total_sales),
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    borderColor: '#2563eb',
                    borderWidth: 1
                },
                {
                    label: 'عدد الطلبات',
                    data: salesData.map(sale => sale.order_count),
                    backgroundColor: 'rgba(5, 150, 105, 0.2)',
                    borderColor: '#059669',
                    borderWidth: 1,
                    yAxisID: 'orders'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'تحليل المبيعات'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'المبيعات (ل.س)'
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
});
</script>
@endpush
