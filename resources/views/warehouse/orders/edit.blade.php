@extends('layouts.warehouse.app')
@section('title', 'تعديل الطلبية - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header">تعديل الطلبية #{{ $order->id }}</h5>
    <div class="card-body">
        <form action="{{ route('warehouse.orders.update', $order->id) }}" method="POST" id="order-form">
            @csrf @method('PUT')

            <!-- جدول العناصر -->
            <table class="table table-hover" id="items-table">
                <thead>
                    <tr>
                        <th>الدواء</th>
                        <th>الشركة</th>
                        <th>الكمية</th>
                        <th>السعر للوحدة</th>
                        <th>المجموع الفرعي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    @foreach ($order->items as $index => $item)
                    <tr class="item-row" data-index="{{ $index }}">
                        <td>
                            <select name="items[{{ $index }}][medicine_id]" class="form-control medicine-select select2" required>
                                <option value="{{ $item->medicine->id }}" selected>{{ $item->medicine->name }}</option>
                                @foreach ($medicines as $medicine)
                                    @if ($medicine->id !== $item->medicine->id)
                                    <option value="{{ $medicine->id }}" data-price="{{ $medicine->selling_price }}">
                                        {{ $medicine->name }} - {{ $medicine->company->name }} : الشركة (متوفر: {{ $medicine->quantity }})
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                            {{$item->medicine->company->name}}
                        </td>
                        <td>
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity-input" value="{{ $item->quantity }}" min="1" required>
                        </td>
                        <td class="price-unit">{{ number_format($item->price_per_unit, 2) }} $</td>
                        <td class="subtotal">{{ number_format($item->subtotal, 2) }} $</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-item">حذف</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- زر إضافة عنصر جديد -->
            <button type="button" class="btn btn-primary mt-3" id="add-item">إضافة دواء جديد</button>

            <!-- إجمالي الطلبية -->
            <h6 class="mt-4">الإجمالي: <span id="total-price">{{ number_format($order->total_price, 2) }}</span> $</h6>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">تحديث الطلبية</button>
                <a href="{{ route('warehouse.orders.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = {{ $order->items->count() }};

    // قالب العنصر الجديد
    const itemTemplate = `
        <tr class="item-row" data-index="{INDEX}">
            <td>
                <select name="items[{INDEX}][medicine_id]" class="form-control medicine-select select2" required>
                    <option value="">اختر دواء</option>
                    @foreach ($medicines as $medicine)
                    <option value="{{ $medicine->id }}" data-price="{{ $medicine->selling_price }}">
                                        {{ $medicine->name }} - {{ $medicine->company->name }} : الشركة (متوفر: {{ $medicine->quantity }})
                    </option>
                    @endforeach
                </select>
            </td>
                        <td>
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
        initializeSelect2(document.querySelector(`[name="items[${itemIndex}][medicine_id]"]`));
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

    // تهيئة Select2
    function initializeSelect2(element) {
        $(element).select2({
            placeholder: "اختر دواء",
            allowClear: true
        });
    }

    // تهيئة Select2 لكل عنصر موجود
    document.querySelectorAll('.medicine-select').forEach(function(select) {
        initializeSelect2(select);
    });
</script>
@endsection
