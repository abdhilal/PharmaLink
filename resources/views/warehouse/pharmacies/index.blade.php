@extends('layouts.warehouse.app')
@section('title', 'الصيدليات')
@section('content')
    <div class="content">
        <h1>الصيدليات</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الصيدلية</th>
                    <th>المدينة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pharmacies as $pharmacy)
                    <tr>
                        <td>{{ $pharmacy->name }}</td>
                        <td>{{ $pharmacy->city->name ?? 'غير محدد' }}</td>
                        <td>
                            <a href="{{ route('pharmacies.show', $pharmacy) }}" class="btn btn-primary">عرض التفاصيل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">لا توجد صيدليات مرتبطة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

<style>
    .content { padding: 20px; }
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 5px 10px; color: white; background-color: #007bff; text-decoration: none; border-radius: 5px; }
</style>
