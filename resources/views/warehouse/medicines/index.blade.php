@extends('layouts.warehouse.app')
@section('title', 'الأدوية')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1>قائمة الأدوية</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="mb-4">
            <a href="{{ route('warehouse.medicines.create') }}" class="btn btn-primary">إضافة دواء جديد</a>
        </div>

        <!-- الأدوية التي ستنفذ -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>الأدوية التي ستنفذ قريبًا</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الشركة</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>تاريخ انتهاء الصلاحية</th>
                            <th>الباركود</th>
                            <th>العرض</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiringMedicines as $medicine)
                            <tr>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->company->name }}</td>
                                <td>{{ number_format($medicine->price, 2) }} ريال</td>
                                <td>{{ $medicine->quantity }}</td>
                                <td>{{ $medicine->date ?? 'غير محدد' }}</td>
                                <td>{{ $medicine->barcode ?? 'غير متوفر' }}</td>
                                <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">لا توجد أدوية ستنفذ قريبًا</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- جميع الأدوية مرتبة حسب الشركة -->
        <div class="card">
            <div class="card-header">
                <h5>جميع الأدوية</h5>
            </div>
            <div class="card-body">
                @forelse($medicines as $companyName => $companyMedicines)
                    <div class="company-section mb-4">
                        <h4 class="company-title">{{ $companyName }}</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>الشركة</th>
                                    <th>السعر الاصلي </th>
                                    <th>سعر مبيع</th>
                                    <th>الكمية</th>
                                    <th>تاريخ انتهاء الصلاحية</th>
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
                                        <td>{{ number_format($medicine->price, 2) }} ريال</td>
                                        <td>{{ number_format($medicine->selling_price, 2) }} ريال</td>
                                        <td>{{ $medicine->quantity }}</td>
                                        <td>{{ $medicine->date ?? 'غير محدد' }}</td>
                                        <td>{{ $medicine->barcode ?? 'غير متوفر' }}</td>
                                        <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                                        <td>
                                            <a href="{{ route('warehouse.medicines.edit', $medicine) }}" class="btn btn-sm btn-warning">تعديل</a>
                                            <form action="{{ route('warehouse.medicines.destroy', $medicine) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    <p>لا توجد أدوية</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 8px 16px; }
    .btn-sm { padding: 4px 8px; }
    .alert-success { padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; }
    .alert-danger { padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; }
    .company-section { margin-bottom: 20px; }
    .company-title {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        width: 100%;
        display: block;
        margin-bottom: 10px;
    }
</style>
