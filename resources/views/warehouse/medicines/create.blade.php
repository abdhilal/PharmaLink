@extends('layouts.warehouse.app')
@section('title', 'إضافة دواء جديد')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h1 class="mb-4">إضافة دواء جديد</h1>

            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('warehouse.medicines.store') }}" method="POST" id="supplyOrderForm">
                        @csrf
                        <div class="row">
                            <!-- معلومات المورد -->
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">اسم المورد</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">اختر موردًا</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>

                            </div>







                            <!-- نوع الحسم -->
                            <div class="col-md-6 mb-3">
                                <label for="discount_type" class="form-label">نوع الحسم</label>
                                <select name="discount_type" id="discount_type" class="form-control" required>
                                    <option value="">اختر نوع الحسم</option>
                                    <option value="per_item">لكل صنف</option>
                                    <option value="total">على الفاتورة</option>
                                </select>
                                @error('discount_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- نسبة الحسم على الفاتورة -->
                            <div class="col-md-6 mb-3" id="total_discount_group" style="display: none;">
                                <label for="discount_percentage" class="form-label">نسبة الحسم (على الفاتورة)</label>
                                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" step="0.01" min="0" value="0">
                                @error('discount_percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- الأصناف -->
                            <div class="col-md-12 mb-3">
                                <h3 class="mb-3">الأدوية</h3>
                                <div id="items_container">
                                    <div class="item-block mb-3 p-3 border rounded" data-index="0">
                                        <h4>الدواء 1</h4>
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">اسم الدواء</label>
                                                <input type="text" name="items[0][name]" class="form-control" list="medicines_list" value="{{ old('items.0.name') }}" required>
                                                <datalist id="medicines_list">
                                                    @foreach ($medicines as $medicine)
                                                        <option value="{{ $medicine->name }}" data-company="{{ $medicine->company->id }}">
                                                    @endforeach
                                                </datalist>
                                                @error('items.0.name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">الشركة</label>
                                                <input type="text" name="items[0][company_name]" class="form-control" list="companies_list" value="{{ old('items.0.company_name') }}" required>
                                                <datalist id="companies_list">
                                                    @foreach ($medicines as $medicine)
                                                        <option value="{{ $medicine->company->name }}">
                                                    @endforeach
                                                </datalist>
                                                @error('items.0.company_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">الكمية</label>
                                                <input type="number" name="items[0][quantity]" class="form-control quantity" min="1" value="{{ old('items.0.quantity') }}" required>
                                                @error('items.0.quantity')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">سعر القطعة الأصلي</label>
                                                <input type="number" name="items[0][price]" class="form-control unit_price" step="0.01" value="{{ old('items.0.price') }}" required>
                                                @error('items.0.price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">نسبة الربح</label>
                                                <input type="number" name="items[0][profit_percentage]" class="form-control" step="0.01" value="{{ old('items.0.profit_percentage') }}" required>
                                                @error('items.0.profit_percentage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">نسبة الحسم على الصنف</label>
                                                <input type="number" name="items[0][discount_percentage]" class="form-control discount_percentage" step="0.01" value="{{ old('items.0.discount_percentage', 0) }}">
                                                @error('items.0.discount_percentage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">الباركود (اختياري)</label>
                                                <input type="text" name="items[0][barcode]" class="form-control" value="{{ old('items.0.barcode') }}">
                                                @error('items.0.barcode')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">العرض (اختياري)</label>
                                                <input type="text" name="items[0][offer]" class="form-control" value="{{ old('items.0.offer') }}">
                                                @error('items.0.offer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">تاريخ انتهاء الصلاحية</label>
                                                <input type="date" name="items[0][date]" class="form-control" value="{{ old('items.0.expiration_date') }}" required>
                                                @error('items.0.expiration_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-danger remove-item mt-2">حذف هذا الصنف</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add_item" class="btn btn-primary mb-3">إضافة صنف جديد</button>
                            </div>

                            <!-- الإجماليات -->
                            <div class="col-md-12 mb-3">
                                <h3 class="mb-3">الإجماليات</h3>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">إجمالي الكمية</label>
                                        <input type="number" name="total_quantity" id="total_quantity" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">التكلفة قبل الحسم</label>
                                        <input type="text" name="total_cost_before_discount" id="total_cost_before_discount" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">قيمة الحسم</label>
                                        <input type="text" name="discount_amount" id="discount_amount" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">التكلفة بعد الحسم</label>
                                        <input type="text" name="total_cost_after_discount" id="total_cost_after_discount" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- ملاحظات -->
                            <div class="col-md-12 mb-3">
                                <label for="note" class="form-label">ملاحظات</label>
                                <textarea name="note" id="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                                @error('note')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- أزرار الإجراء -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">إضافة الدواء</button>
                                <a href="{{ route('warehouse.medicines.index') }}" class="btn btn-secondary">رجوع</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .btn {
            padding: 8px 16px;
            margin-right: 5px;
        }
        .alert-success {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
        }
        .text-danger {
            color: #dc3545;
            font-size: 0.9em;
        }
        .item-block {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .item-block h4 {
            margin-bottom: 15px;
            color: #333;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let itemIndex = 1;

        // إضافة صنف جديد
        $('#add_item').click(function() {
            let discountType = $('#discount_type').val(); // الحصول على نوع الحسم المحدد
            let isTotalDiscount = discountType === 'total'; // هل الحسم على الفاتورة؟

            let newItem = `
                <div class="item-block mb-3 p-3 border rounded" data-index="${itemIndex}">
                    <h4>الدواء ${itemIndex + 1}</h4>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">اسم الدواء</label>
                            <input type="text" name="items[${itemIndex}][name]" class="form-control" list="medicines_list" required>
                            <datalist id="medicines_list">
                                @foreach ($medicines as $medicine)
                                    <option value="{{ $medicine->name }}" data-company="{{ $medicine->company->id }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">الشركة</label>
                            <input type="text" name="items[${itemIndex}][company_name]" class="form-control" list="companies_list" required>
                            <datalist id="companies_list">
                                @foreach ($medicines as $medicine)
                                    <option value="{{ $medicine->company->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">الكمية</label>
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" min="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">سعر القطعة الأصلي</label>
                            <input type="number" name="items[${itemIndex}][price]" class="form-control unit_price" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">نسبة الربح</label>
                            <input type="number" name="items[${itemIndex}][profit_percentage]" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">نسبة الحسم على الصنف</label>
                            <input type="number" name="items[${itemIndex}][discount_percentage]" class="form-control discount_percentage" step="0.01" value="0" ${isTotalDiscount ? 'disabled' : ''}>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">الباركود (اختياري)</label>
                            <input type="text" name="items[${itemIndex}][barcode]" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">العرض (اختياري)</label>
                            <input type="text" name="items[${itemIndex}][offer]" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">تاريخ انتهاء الصلاحية</label>
                            <input type="date" name="items[${itemIndex}][date]" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger remove-item mt-2">حذف هذا الصنف</button>
                        </div>
                    </div>
                </div>`;
            $('#items_container').append(newItem);
            itemIndex++;
            updateTotals();
        });

        // حذف صنف
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-block').remove();
            updateIndexes();
            updateTotals();
        });

        // تحديث الفهارس بعد الحذف
        function updateIndexes() {
            let newIndex = 0;
            $('.item-block').each(function() {
                $(this).attr('data-index', newIndex);
                $(this).find('h4').text(`الدواء ${newIndex + 1}`);
                $(this).find('input, select').each(function() {
                    let name = $(this).attr('name');
                    if (name) {
                        name = name.replace(/\[\d+\]/, `[${newIndex}]`);
                        $(this).attr('name', name);
                    }
                });
                newIndex++;
            });
            itemIndex = newIndex;
        }

        // التحكم في نوع الحسم
        $('#discount_type').change(function() {
            let discountType = $(this).val(); // الحصول على نوع الحسم المحدد
            let isTotalDiscount = discountType === 'total'; // هل الحسم على الفاتورة؟

            // إظهار أو إخفاء مجموعة الحسم على الفاتورة
            if (isTotalDiscount) {
                $('#total_discount_group').show();
            } else {
                $('#total_discount_group').hide();
            }

            // تحديث حالة حقول "نسبة الحسم على الصنف" لكل الأصناف
            $('.discount_percentage').each(function() {
                $(this).prop('disabled', isTotalDiscount).val(0);
            });

            updateTotals();
        });

        // تحديث الإجماليات عند التغيير
        $(document).on('input', '.quantity, .unit_price, .discount_percentage, #discount_percentage', updateTotals);

        function updateTotals() {
            let totalQuantity = 0;
            let totalCostBeforeDiscount = 0;
            let totalDiscountAmount = 0;

            $('.item-block').each(function() {
                let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                let unitPrice = parseFloat($(this).find('.unit_price').val()) || 0;
                let discountPercentage = parseFloat($(this).find('.discount_percentage').val()) || 0;
                let subtotal = quantity * unitPrice;
                let discountAmount = $('#discount_type').val() === 'per_item' ? (subtotal * discountPercentage / 100) : 0;

                totalQuantity += quantity;
                totalCostBeforeDiscount += subtotal;
                totalDiscountAmount += discountAmount;
            });

            if ($('#discount_type').val() === 'total') {
                let discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
                totalDiscountAmount = totalCostBeforeDiscount * discountPercentage / 100;
            }

            $('#total_quantity').val(totalQuantity);
            $('#total_cost_before_discount').val(totalCostBeforeDiscount.toFixed(2));
            $('#discount_amount').val(totalDiscountAmount.toFixed(2));
            $('#total_cost_after_discount').val((totalCostBeforeDiscount - totalDiscountAmount).toFixed(2));
        }

        // تشغيل التحديث عند التحميل
        $(document).ready(function() {
            let discountType = $('#discount_type').val(); // الحصول على نوع الحسم المحدد
            let isTotalDiscount = discountType === 'total'; // هل الحسم على الفاتورة؟

            // تحديث حالة حقول "نسبة الحسم على الصنف" لكل الأصناف
            $('.discount_percentage').each(function() {
                $(this).prop('disabled', isTotalDiscount).val(0);
            });

            updateTotals();
        });
    </script>
@endsection
