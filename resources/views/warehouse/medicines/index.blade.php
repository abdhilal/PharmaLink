@extends('layouts.app') <!-- استخدام القالب الأساسي -->

@section('title', 'أدوية المستودع') <!-- عنوان الصفحة -->

@section('content')
    <div class="content">
        <!-- عنوان رئيسي للصفحة -->
        <h1>أدوية المستودع #{{ $warehouse->id }}</h1>

        <!-- زر للانتقال إلى صفحة إضافة دواء جديد -->
        <a href="{{ route('warehouse.medicines.create') }}" class="btn">إضافة دواء جديد</a>

        <!-- جدول لعرض الأدوية -->
        <table>
            <thead>
                <tr>
                    <th>اسم الدواء</th>
                    <th>الشركة</th>
                    <th>السعر</th>
                    <th>الكمية المتاحة</th>
                    <th>العروض</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine) <!-- حلقة لعرض كل دواء -->
                    <tr>
                        <td>{{ $medicine->name }}</td> <!-- اسم الدواء -->
                        <td>{{ $medicine->company->name }}</td> <!-- اسم الشركة -->
                        <td>{{ number_format($medicine->price, 2) }} ريال</td> <!-- السعر بصيغة منسقة -->
                        <td>{{ $medicine->quantity }}</td> <!-- الكمية المتاحة -->
                        <td>{{ $medicine->offer ?? 'لا يوجد' }}</td> <!-- العرض إن وجد -->
                    </tr>
                @empty <!-- إذا لم يكن هناك أدوية -->
                    <tr>
                        <td colspan="5">لا توجد أدوية مسجلة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<style>
    /* تنسيق بسيط لتحسين المظهر */
    .content { padding: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    .btn:hover { background-color: #0056b3; }
</style>