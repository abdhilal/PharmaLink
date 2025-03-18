@extends('layouts.app')
@section('title', 'تفاصيل الصيدلية')
@section('content')
    <div class="content">
        <h1>تفاصيل الصيدلية: {{ $pharmacy->name }}</h1>

        <!-- معلومات أساسية -->
        <div class="section">
            <h2>معلومات أساسية</h2>
            <p><strong>الاسم:</strong> {{ $pharmacy->name }}</p>
            <p><strong>المدينة:</strong> {{ $pharmacy->city->name ?? 'غير محدد' }}</p>
            <p><strong>تاريخ التسجيل:</strong> {{ $pharmacy->created_at->format('Y-m-d') }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $pharmacy->email }}</p>
        </div>

        <!-- إحصائيات الطلبيات -->
        <div class="section">
            <h2>إحصائيات الطلبيات</h2>
            <p><strong>الطلبيات المعلقة:</strong> {{ $pharmacy->orders->where('status', 'pending')->count() }}</p>
            <p><strong>الطلبيات المسلمة:</strong> {{ $pharmacy->orders->where('status', 'delivered')->count() }}</p>
            <p><strong>الطلبيات الملغاة:</strong> {{ $pharmacy->orders->where('status', 'cancelled')->count() }}</p>
            <p><strong>إجمالي قيمة الطلبيات المسلمة:</strong> {{ number_format($pharmacy->orders->where('status', 'delivered')->sum('total_price'), 2) }} ريال</p>
            <h3>آخر 5 طلبيات</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>المعرف</th>
                        <th>السعر الإجمالي</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pharmacy->orders->sortByDesc('created_at')->take(5) as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order) }}">{{ $order->id }}</a></td>
                            <td>{{ number_format($order->total_price, 2) }} ريال</td>
                            <td>{{ $order->status === 'pending' ? 'معلقة' : ($order->status === 'delivered' ? 'مسلمة' : 'ملغاة') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">لا توجد طلبيات حديثة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- حالة الرصيد -->
        <div class="section">
            <h2>حالة الرصيد</h2>
            @php
                $account = $pharmacy->accounts->first(); // نفترض أن لكل صيدلية حساب واحد مع المستودع
                $balance = $account->balance;
                $status = $balance > 0 ? 'مستحق' : ($balance < 0 ? 'دائن' : 'متساوٍ');
            @endphp
            <p><strong>الرصيد الحالي:</strong> {{ number_format(abs($balance), 2) }} ريال ({{ $status }})</p>
            <p><strong>إجمالي الديون:</strong> {{ number_format($account->transactions->where('type', 'debt')->sum('amount'), 2) }} ريال</p>
            <p><strong>إجمالي المدفوعات:</strong> {{ number_format($account->transactions->where('type', 'payment')->sum('amount'), 2) }} ريال</p>
            <h3>آخر 5 معاملات</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المبلغ</th>
                        <th>النوع</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($account->transactions->sortByDesc('created_at')->take(5) as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                            <td>{{ number_format($transaction->amount, 2) }} ريال</td>
                            <td>{{ $transaction->type === 'debt' ? 'دين' : 'دفعة' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">لا توجد معاملات حديثة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('pharmacies.index') }}" class="btn btn-primary">العودة إلى الصيدليات</a>
    </div>
@endsection

<style>
    .content { padding: 20px; }
    .section { margin-bottom: 30px; }
    .table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    .btn { padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
