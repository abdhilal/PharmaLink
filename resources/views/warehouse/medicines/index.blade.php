@extends('layouts.warehouse.app')
@section('title', 'الأدوية')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">قائمة الأدوية</h1>
            <a href="{{ route('warehouse.medicines.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i> إضافة دواء جديد
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm animate__animated animate__fadeIn">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger shadow-sm animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif

        <!-- الأدوية التي ستنفذ -->
        <div class="card mb-4 shadow-sm border-0 animate__animated animate__fadeInUp">
            <div class="card-header bg-gradient-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> الأدوية التي ستنفذ قريبًا</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الشركة</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الباركود</th>
                                <th>العرض</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiringMedicines as $medicine)
                                <tr>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->company->name }}</td>
                                    <td>{{ number_format($medicine->price, 2) }} $</td>
                                    <td>{{ $medicine->quantity }}</td>
                                    <td>{{ $medicine->date ?? 'غير محدد' }}</td>
                                    <td>{{ $medicine->barcode ?? 'غير متوفر' }}</td>
                                    <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">لا توجد أدوية ستنفذ قريبًا</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- جميع الأدوية -->
        <div class="card shadow-sm border-0 animate__animated animate__fadeInUp">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0"><i class="fas fa-pills me-2"></i> جميع الأدوية</h5>
            </div>
            <p></p>

            <div class="card-body">
                @forelse($medicines as $companyName => $companyMedicines)
                    <div class="company-section mb-5">
                        <h4 class="company-title">{{ $companyName }}</h4>
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>الشركة</th>
                                        <th>السعر الأصلي</th>
                                        <th>سعر المبيع</th>
                                        <th>الكمية</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>الباركود</th>
                                        <th>العرض</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companyMedicines as $medicine)
                                        <tr>
                                            <td>{{ $medicine->name }}</td>
                                            <td>{{ $medicine->company->name }}</td>
                                            <td>{{ number_format($medicine->price, 2) }} $</td>
                                            <td>{{ number_format($medicine->selling_price, 2) }} $</td>
                                            <td>{{ $medicine->quantity }}</td>
                                            <td>{{ $medicine->date ?? 'غير محدد' }}</td>
                                            <td>{{ $medicine->barcode ?? 'غير متوفر' }}</td>
                                            <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('warehouse.medicines.edit', $medicine) }}" class="btn btn-sm btn-warning mb-6 mr-3">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('warehouse.medicines.destroy', $medicine) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger " >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">لا توجد أدوية متاحة حاليًا</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* الأساسيات */
    body {
        background-color: #f5f7fa;
        font-family: 'Arial', sans-serif;
    }

    .page-title {
        color: #2c3e50;
        font-weight: bold;
        font-size: 2rem;
        margin-bottom: 0;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
    }

    .card-header {
        padding: 15px 20px;
        font-weight: 600;
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffca28, #ffeb3b);
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #0288d1, #4fc3f7);
    }

    /* الجداول */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table-modern th {
        background-color: #e9ecef;
        color: #343a40;
        font-weight: 600;
        padding: 15px;
        text-align: center;
    }

    .table-modern td {
        background-color: #fff;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border-radius: 8px;
    }

    .table-modern tbody tr:hover td {
        background-color: #f8f9fa;
        transition: background-color 0.3s;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* الأزرار والتنبيهات */
    .btn {
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn-group .btn {
        border-radius: 5px;
        padding: 6px 12px;
    }

    .alert {
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
    }

    /* عنوان الشركة */
    .company-title {
        background: linear-gradient(45deg, #1976d2, #42a5f5);
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
</style>
