@extends('layouts.warehouse.app')

@section('title', 'الطلبيات العاجلة')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">الطلبيات العاجلة</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ملاحظة</th>
                                    <th>يبعد عنك</th>
                                    <th>حالة الطلب</th>
                                    <th>عدد العناصر</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($urgentorders)




                                    @foreach ($urgentorders as $order)
                                        <tr>
                                            <td>{{ $order->note ?? 'لا توجد ملاحظة' }}</td>
                                            <td>{{ $order->distance ?? '' }} كم</td>
                                            <td>
                                                <span
                                                    class="badge {{ $order->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                    {{ $order->status === 'pending' ? 'معلق' : $order->status }}
                                                </span>
                                            </td>
                                            <td>{{ $order->items->count() }}</td>
                                            <td>
                                                <a href="{{ route('warehouse.urgentorder.show', [$order->id, $order->pharmacy_id]) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bx bx-detail me-1"></i> عرض التفاصيل
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            لا توجد طلبيات عاجلة حاليًا
                                        </td>
                                    </tr>
                                @endif



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* تحسينات التصميم */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }

        .card.shadow-sm {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .card-header.bg-primary {
            background-color: #3B82F6 !important;
            padding: 1rem 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transition: background-color 0.3s ease;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }

        .btn-info {
            background-color: #3B82F6;
            border-color: #3B82F6;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-info:hover {
            background-color: #2563EB;
            border-color: #2563EB;
        }

        .btn-sm i {
            font-size: 1rem;
        }

        .table-responsive {
            border-radius: 0 0 12px 12px;
            overflow-x: auto;
        }
    </style>
@endsection
