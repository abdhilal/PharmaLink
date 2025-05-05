@extends('layouts.warehouse.app')
@section('title', 'الأدوية')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- مستطيل الإعلان -->
        @if ($offer && ($offer->image || $offer->title))
            <div class="advertisement-rectangle shadow-lg animate__animated animate__fadeIn">
                @if ($offer->image && $offer->title)
                    <img src="{{ asset('storage/' . $offer->image) }}" alt="إعلان" class="ad-image">
                    <div class="ad-overlay">
                        <h1 class="ad-title">{{ $offer->title }}</h1>
                    </div>
                @elseif ($offer->image)
                    <img src="{{ asset('storage/' . $offer->image) }}" alt="إعلان" class="ad-image">
                @elseif ($offer->title)
                    <div class="ad-text-only">
                        <h1 class="ad-title">{{ $offer->title }}</h1>
                    </div>
                @endif
            </div>
        @endif


        <!-- نموذج رفع الإعلان -->
        <div class="card mb-4 shadow-sm border-0 animate__animated animate__fadeInUp">
            <div class="card-body">
                <form action="{{ route('warehouse.medicines.offer') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-12">
                        <h5 class="mb-3"><i class="fas fa-bullhorn me-2"></i> إدارة الإعلان</h5>
                    </div>
                    <div class="col-md-4">
                        <label for="offer_image" class="form-label">صورة الإعلان</label>
                        <input type="file" name="image" id="offer_image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label for="offer_title" class="form-label">نص الإعلان</label>
                        <input type="text" name="title" id="offer_title" class="form-control" placeholder="أدخل نص الإعلان">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success me-2"><i class="fas fa-upload me-2"></i> رفع الإعلان</button>
                        <button type="submit" formaction="{{ route('warehouse.medicines.offer') }}" class="btn btn-danger"><i class="fas fa-trash me-2"></i> حذف الإعلان</button>
                    </div>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm animate__animated animate__fadeIn">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger shadow-sm animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif

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
                                        <th>سعر المبيع</th>
                                        <th>الكمية</th>
                                        <th>العرض</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companyMedicines as $medicine)
                                        <tr>
                                            <td>{{ $medicine->name }}</td>
                                            <td>{{ $medicine->company->name }}</td>
                                            <td>{{ number_format($medicine->selling_price, 2) }} $</td>
                                            <td>{{ $medicine->quantity }}</td>
                                            <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if ($medicine->is_hidden)
                                                        <form action="{{ route('warehouse.medicines.is_hidden', $medicine->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من إظهار الدواء؟');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-eye"></i></button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('warehouse.medicines.is_hidden', $medicine->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من إخفاء الدواء؟');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-eye-slash"></i></button>
                                                        </form>
                                                    @endif
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

    .card {
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
    }

    .card-header {
        padding: 15px 20px;
        font-weight: 600;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #0288d1, #4fc3f7);
    }

    /* مستطيل الإعلان */
    .advertisement-rectangle {
        width: 100%;
        height: 350px;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        margin-bottom: 30px;
        background: #007bff;
    }

    .ad-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(70%);
    }

    .ad-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.3);
    }

    .ad-title {
        color: #fff;
        font-size: 2.5rem;
        font-weight: bold;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        margin: 0;
    }

    .ad-text-only {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
    }

    /* النموذج */
    .form-control {
        border-radius: 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 500;
        color: #343a40;
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
