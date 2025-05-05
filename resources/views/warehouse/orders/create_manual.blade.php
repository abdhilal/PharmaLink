@extends('layouts.warehouse.app')
@section('title', 'إنشاء طلبية يدوية - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">إنشاء طلبية يدوية للصيدليات</h5>
    <div class="card-body">
        <form action="{{ route('warehouse.orders.store_manual') }}" method="POST" id="manual-order-form">
            @csrf

            <!-- اختيار الصيدلية -->
            <div class="mb-3">
                <label class="form-label">الصيدلية</label>
                <select name="pharmacy_id" class="form-select" required>
                    <option value="">اختر الصيدلية</option>
                    @foreach ($pharmacies as $pharmacy)
                        <option value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- جدول العناصر -->
            <table class="table table-hover" id="items-table">
                <thead>
                    <tr>
                        <th>الدواء</th>
                        <th>الكمية</th>
                        <th>السعر للوحدة</th>
                        <th>المجموع الفرعي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <!-- سيتم إضافة العناصر ديناميكيًا -->
                </tbody>
            </table>

            <!-- زر إضافة عنصر -->
            <button type="button" class="btn btn-primary mt-3" id="add-item">إضافة دواء</button>

            <!-- إجمالي الطلبية -->
            <h6 class="mt-4">الإجمالي: <span id="total-price">0.00</span> $</h6>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">إنشاء الطلبية</button>
                <a href="{{ route('warehouse.orders.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>
</div>

@php
    $medicinesData = $medicines->map(function ($medicine) {
        return [
            'id' => $medicine->id,
            'name' => $medicine->name,
            'price' => $medicine->selling_price,
            'quantity' => $medicine->quantity,
            'company_name' => $medicine->company ? $medicine->company->name : 'غير محدد' // افتراض علاقة company
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
            <td class="price-unit">-</td>
            <td class="subtotal">-</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-item">حذف</button>
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
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
            updateTotalPrice();
        }
    });

    // تحديث السعر عند تغيير الدواء أو الكمية
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('medicine-select')) {
            const row = e.target.closest('.item-row');
            const price = e.target.selectedOptions[0].dataset.price || 0;
            row.querySelector('.price-unit').textContent = Number(price).toFixed(2) + ' $';
            updateSubtotal(row);
        }
        if (e.target.classList.contains('quantity-input')) {
            updateSubtotal(e.target.closest('.item-row'));
        }
    });

    // حساب المجموع الفرعي
    function updateSubtotal(row) {
        const quantity = row.querySelector('.quantity-input').value || 0;
        const price = row.querySelector('.medicine-select').selectedOptions[0].dataset.price || 0;
        const subtotal = quantity * price;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2) + ' $';
        updateTotalPrice();
    }

    // حساب الإجمالي
    function updateTotalPrice() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(subtotal) {
            total += Number(subtotal.textContent.replace(' $', '')) || 0;
        });
        document.getElementById('total-price').textContent = total.toFixed(2);
    }
</script>

@endsection
