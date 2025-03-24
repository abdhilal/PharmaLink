@extends('layouts.warehouse.app')
@section('title', 'الصيدليات - PharmaLink')
@section('content')
<div class="card">
    <h5 class="card-header d-flex justify-content-between align-items-center">
        <span>الصيدليات</span>
    </h5>
    <div class="card-body">
        <form method="GET" action="{{ route('warehouse.pharmacies.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو المدينة" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الصيدلية</th>
                    <th>المدينة</th>
                    <th>عدد الطلبيات</th>
                    <th>الدين المستحق</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pharmacies as $index => $pharmacy)
                <tr>
                    <td>{{ $pharmacies->firstItem() + $index }}</td>
                    <td>{{ $pharmacy->name }}</td>
                    <td>{{ $pharmacy->city->name ?? 'غير محدد' }}</td>
                    <td>{{ $pharmacy->orders_count }}</td>
                    <td>{{ number_format($pharmacy->accounts->first()->balance ?? 0, 2) }} ريال</td>
                    <td>
                        <a href="{{ route('warehouse.pharmacies.show', $pharmacy->id) }}" class="btn btn-sm btn-info">تفاصيل</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">لا توجد صيدليات مرتبطة</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $pharmacies->links() }}
    </div>
</div>
@endsection
