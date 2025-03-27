@extends('layouts.pharmacy.app')

@section('title', 'طلبية عاجلة')

@section('content')
<div class="card">
    <h5 class="card-header">إنشاء طلبية عاجلة</h5>
    <div class="card-body">
        <form action="{{ route('pharmacy.urgentorder.store') }}" method="POST" id="manual-order-form">
            @csrf

            <!-- جدول العناصر -->
            <table class="table table-hover" id="items-table">
                <thead>
                    <tr>
                        <th>اسم الدواء مع اسم الشركة</th>
                        <th>الكمية المطلوبة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <!-- سيتم إضافة العناصر ديناميكيًا -->
                </tbody>
            </table>

            <!-- زر إضافة عنصر -->
            <button type="button" class="btn btn-primary mt-3" id="add-item">إضافة دواء</button>
<label for="">اضف ملاحظة</label><br>
            <input type="text" name="note">

            <div class="mt-4">
                <button type="submit" class="btn btn-success">إنشاء الطلبية</button>
                <a href="{{ route('pharmacy.warehouses.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 0;

    // قالب العنصر الجديد
    const itemTemplate = `
        <tr class="item-row" data-index="{INDEX}">
            <td>
                <input type="text" name="items[{INDEX}][name]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="items[{INDEX}][quantity]" class="form-control quantity-input" min="1" required>
            </td>
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

    // حذف عنصر عند الضغط على زر الحذف
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });

    // منع إرسال الطلب إذا كان الجدول فارغًا
    document.getElementById('manual-order-form').addEventListener('submit', function(event) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length === 0) {
            alert('يجب إضافة دواء واحد على الأقل قبل إرسال الطلب.');
            event.preventDefault();
        }
    });

</script>
@endsection
