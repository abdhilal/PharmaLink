@extends('layouts.warehouse.app')

@section('title', 'تعديل طلبية توريد')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h1 class="mb-4">تعديل طلبية توريد</h1>

            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('warehouse.supply_order.update', $order->id) }}" method="POST" id="supplyOrderForm">
                        @csrf

                        <div class="row">
                            <!-- معلومات المورد -->
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">اسم المورد</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="{{ $order->supplier->id }}">{{ $order->supplier->name }}</option>
                                    @foreach ($suppliers as $supp)
                                        <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- نوع الحسم -->
                            <div class="col-md-6 mb-3">
                                <label for="discount_type" class="form-label">نوع الحسم</label>
                                <select name="discount_type" id="discount_type" class="form-control" required>
                                    <option value="{{ $order->discount_type }}">{{ $order->discount_type }}</option>
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
                                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" step="0.01" min="0" value="{{ $order->discount_percentage }}">
                                @error('discount_percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- الأصناف -->
                            <div class="col-md-12 mb-3">
                                <h3 class="mb-3">الأدوية</h3>
                                <div id="items_container">
                                    @foreach ($order->items as $index => $item)
                                        <div class="item-block mb-3 p-3 border rounded" data-index="{{ $index }}">
                                            <h4>الدواء {{ $index + 1 }}</h4>
                                            <div class="row">
                                                <!-- إضافة حقل مخفي لحفظ ID الصنف -->
                                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">اسم الدواء</label>
                                                    <input type="text" name="items[{{ $index }}][name]" class="form-control" list="medicines_list" value="{{ $item->medicine->name }}" required>
                                                    <datalist id="medicines_list">
                                                        @foreach ($medicines as $medicine)
                                                            <option value="{{ $medicine->name }}" data-company="{{ $medicine->company->id }}">
                                                        @endforeach
                                                    </datalist>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">الشركة</label>
                                                    <input type="text" name="items[{{ $index }}][company_name]" class="form-control" list="companies_list" value="{{ $item->medicine->company->name }}" required>
                                                    <datalist id="companies_list">
                                                        @foreach ($medicines as $medicine)
                                                            <option value="{{ $medicine->company->name }}">
                                                        @endforeach
                                                    </datalist>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">الكمية</label>
                                                    <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" min="1" value="{{ $item->quantity }}" required>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">سعر القطعة الأصلي</label>
                                                    <input type="number" name="items[{{ $index }}][price]" class="form-control unit_price" step="0.01" value="{{ $item->unit_price }}" required>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">نسبة الربح</label>
                                                    <input type="number" name="items[{{ $index }}][profit_percentage]" class="form-control" step="0.01" value="{{ $item->medicine->profit_percentage }}" required>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">نسبة الحسم على الصنف</label>
                                                    <input type="number" name="items[{{ $index }}][discount_percentage]" class="form-control discount_percentage" step="0.01" value="{{ $item->discount_percentage }}">
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">الباركود (اختياري)</label>
                                                    <input type="text" name="items[{{ $index }}][barcode]" class="form-control" value="{{ $item->medicine->barcode }}">
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">العرض (اختياري)</label>
                                                    <input type="text" name="items[{{ $index }}][offer]" class="form-control" value="{{ $item->medicine->offer }}">
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">تاريخ انتهاء الصلاحية</label>
                                                    <input type="date" name="items[{{ $index }}][date]" class="form-control" value="{{ $item->medicine->date }}" required>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-danger remove-item mt-2">حذف هذا الصنف</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add_item" class="btn btn-primary mb-3">إضافة صنف جديد</button>
                            </div>

                            <!-- ملاحظات -->
                            <div class="col-md-12 mb-3">
                                <label for="note" class="form-label">ملاحظات</label>
                                <textarea name="note" id="note" class="form-control" rows="3">{{ $order->note }}</textarea>
                                @error('note')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- أزرار الإجراء -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                {{-- <a href="{{ route('warehouse.supply_order.index') }}" class="btn btn-secondary">رجوع</a> --}}
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
        let itemIndex = {{ count($order->items) }};

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
        });

        // حذف صنف
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-block').remove();
            updateIndexes();
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
        });
    </script>
@endsection
