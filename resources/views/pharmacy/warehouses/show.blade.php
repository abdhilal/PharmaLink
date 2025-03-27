@extends('layouts.pharmacy.app')
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

        <!-- التنبيهات -->
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
                <form action="{{ route('pharmacy.cart.addMultiple') }}" method="POST">
                    @csrf
                    <input type="hidden" name="warehouse_id" value="{{ $warehouseId }}">

                    @forelse($medicines as $companyName => $companyMedicines)
                    <div class="company-section mb-5">
                        <h4 class="company-title">{{ $companyName }}</h4>
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>الشركة</th>
                                        <th>السعر</th>
                                        <th>العرض</th>
                                        <th>الكمية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companyMedicines as $medicine)
                                    @if(!$medicine->is_hidden)
                                    <tr>
                                        <td>{{ $medicine->name }}</td>
                                        <td>{{ $medicine->company->name }}</td>
                                        <td>{{ number_format($medicine->selling_price, 2) }} $</td>
                                        <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                                        <td class="quantity-cell">
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <input type="number" name="items[{{ $medicine->id }}][quantity]" class="form-control quantity-input" min="0" value="0">
                                                <input type="hidden" name="items[{{ $medicine->id }}][medicine_id]" value="{{ $medicine->id }}">
                                            </div>
                                        </td>
                                    </tr>
                                    @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">لا توجد أدوية متاحة حاليًا</p>
                @endforelse

                    <!-- زر الإرسال العائم على شكل سلة -->
                    <button type="submit" class="btn btn-success floating-cart-btn" id="floatingCartBtn">
                        <i class="fas fa-shopping-cart me-2"></i> إضافة إلى السلة
                    </button>
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

    /* تنسيق حقل الكمية */
    .quantity-cell .input-group {
        display: inline-flex;
        align-items: center;
    }

    .quantity-input {
        text-align: center;
        border-radius: 8px 0 0 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .add-quantity {
        border-radius: 0 8px 8px 0;
        padding: 6px 10px;
    }

    .add-quantity:hover {
        background-color: #0277bd;
        color: #fff;
    }

    /* زر الإرسال العائم */
    .floating-cart-btn {
        position: fixed;
        bottom: 30px;
        left: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .floating-cart-btn:hover {
        width: 180px;
        border-radius: 30px;
        transform: translateY(-5px);
    }

    .floating-cart-btn .me-2 {
        display: none;
    }

    .floating-cart-btn:hover .me-2 {
        display: inline;
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

    /* التنبيهات */
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
</style>

<script>
    // إصلاح زر + لزيادة الكمية
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                let currentValue = parseInt(input.value);
                const max = parseInt(input.max);
                if (currentValue < max) {
                    input.value = currentValue + 1;
                }
            });
        });

        // إصلاح السلة لتبقى ثابتة
        const floatingCartBtn = document.getElementById('floatingCartBtn');
        window.addEventListener('scroll', function() {
            floatingCartBtn.style.transform = 'translateY(0)';
        });
    });
</script>
@endsection
