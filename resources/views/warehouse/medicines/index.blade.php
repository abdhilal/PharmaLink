@extends('layouts.app')
@section('title', 'الأدوية')
@section('content')
    <div class="content">
        <h1>الأدوية</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <a href="{{ route('warehouse.medicines.create') }}" class="btn btn-primary">إضافة دواء جديد</a>
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الشركة</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>العرض</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->company->name }}</td>
                        <td>{{ number_format($medicine->price, 2) }} ريال</td>
                        <td>{{ $medicine->quantity }}</td>
                        <td>{{ $medicine->offer ?? 'لا يوجد' }}</td>
                        <td>
                            <a href="{{ route('warehouse.medicines.edit', $medicine) }}" class="btn btn-primary">تعديل</a>
                            <form action="{{ route('warehouse.medicines.destroy', $medicine) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">لا توجد أدوية</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<style>
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 5px 10px; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px; }
    .btn-primary { background-color: #007bff; }
    .btn-danger { background-color: #dc3545; }
    .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
</style>
