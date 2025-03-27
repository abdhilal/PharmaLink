@extends('layouts.warehouse.app')

@section('title', 'إنشاء طلبية يدوية - PharmaLink')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">إنشاء طلبية يدوية للصيدليات</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('warehouse.urgentorder.store_manual') }}" method="POST" id="manual-order-form">
                    @csrf

                    <!-- اختيار الصيدلية -->
                    <div class="mb-4">
                        <label for="pharmacy_id" class="form-label fw-bold">معرف الصيدلية</label>
                        <input type="number" name="pharmacy_id" id="pharmacy_id" value="{{ $pharmacy_id }}" class="form-control" readonly>
                    </div>

                    <!-- العناصر المطلوبة من الطلبية العاجلة -->
                    @if(isset($order) && $order->items->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="fw-bold">العناصر المطلوبة من الطلبية العاجلة:</h6>
                            <ul class="list-group mb-3">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $item['name'] }}</span>
                                        <span class="badge bg-info">{{ $item['quantity'] }} قطعة</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- جدول إضافة العناصر -->
                    <h6 class="fw-bold mb-3">إضافة الأدوية للطلبية:</h6>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle" id="items-table">
                            <thead class="table-light">
                                <tr>
                                    <th>الدواء</th>
                                    <th>الكمية</th>
                                    <th>السعر للوحدة ($)</th>
                                    <th>المجموع الفرعي ($)</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                <!-- سيتم إضافة العناصر ديناميكيًا -->
                            </tbody>
                        </table>
                    </div>

                    <!-- زر إضافة عنصر -->
                    <button type="button" class="btn btn-primary mt-3" id="add-item">
                        <i class="bx bx-plus me-1"></i> إضافة دواء
                    </button>

                    <!-- إجمالي الطلبية -->
                    <h6 class="mt-4 fw-bold">الإجمالي: <span id="total-price">0.00</span> $</h6>

                    <!-- أزرار الإجراءات -->
                    <div class="mt-4 d-flex gap-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bx bx-check me-1"></i> إنشاء الطلبية
                        </button>
                        <a href="{{ route('warehouse.orders.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> رجوع
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
    $medicinesData = $medicines->map(function ($medicine) {
        return [
            'id' => $medicine->id,
            'name' => $medicine->name,
            'price' => $medicine->selling_price,
            'quantity' => $medicine->quantity,
            'company_name' => $medicine->company ? $medicine->company->name : 'غير محدد'
        ];
    })->toArray();
@endphp

<script>
    let itemIndex = 0;
    const medicines = @json($medicinesData);

    // قالب العنصر الجديد
    const itemTemplate = `
        <tr class="item-row" data-index="{INDEX}">
            <td>
                <select name="items[{INDEX}][medicine_id]" class="form-control medicine-select" required>
                    <option value="">اختر دواء</option>
                    ${medicines.map(m => `<option value="${m.id}" data-price="${m.price}" data-max="${m.quantity}">${m.name} - ${m.company_name} (متوفر: ${m.quantity})</option>`).join('')}
                </select>
            </td>
            <td>
                <input type="number" name="items[{INDEX}][quantity]" class="form-control quantity-input" min="1" required>
            </td>
            <td class="price-unit">0.00</td>
            <td class="subtotal">0.00</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-item">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        </tr>
    `;

    // إضافة عنصر جديد
    document.getElementById('add-item').addEventListener('click', function() {
        const newRow = itemTemplate.replace(/{INDEX}/g, itemIndex);
        document.getElementById('items-body').insertAdjacentHTML('beforeend', newRow);
        itemIndex++;
    });

    // حذف عنصر
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
            updateTotalPrice();
        }
    });

    // تحديث السعر عند تغيير الدواء أو الكمية
    document.addEventListener('change', function(e) {
        const row = e.target.closest('.item-row');
        if (e.target.classList.contains('medicine-select')) {
            const price = e.target.selectedOptions[0].dataset.price || 0;
            row.querySelector('.price-unit').textContent = Number(price).toFixed(2);
            updateSubtotal(row);
        }
        if (e.target.classList.contains('quantity-input')) {
            updateSubtotal(row);
        }
    });

    // حساب المجموع الفرعي
    function updateSubtotal(row) {
        const quantity = row.querySelector('.quantity-input').value || 0;
        const price = row.querySelector('.medicine-select').selectedOptions[0].dataset.price || 0;
        const subtotal = quantity * price;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        updateTotalPrice();
    }

    // حساب الإجمالي
    function updateTotalPrice() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(subtotal) {
            total += Number(subtotal.textContent) || 0;
        });
        document.getElementById('total-price').textContent = total.toFixed(2);
    }
</script>

<style>
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

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        color: #4a5568;
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

    .btn-primary, .btn-success, .btn-secondary {
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #3B82F6;
        border-color: #3B82F6;
    }

    .btn-primary:hover {
        background-color: #2563EB;
        border-color: #2563EB;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    .btn i {
        font-size: 1rem;
    }

    .list-group-item {
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .badge.bg-info {
        font-size: 0.85rem;
    }
</style>
@endsection
