@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <!-- رأس الصفحة -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">الإشعارات</h1>
            <div class="flex gap-4">
                <!-- زر حذف الإشعارات القديمة -->
                <form action="{{ route('notifications.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        حذف الإشعارات القديمة
                    </button>
                </form>
            </div>
        </div>

        <!-- فلترة الإشعارات -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-4">
                <button onclick="filterNotifications('all')" 
                        class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors filter-btn active">
                    الكل
                </button>
                <button onclick="filterNotifications('inventory')" 
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors filter-btn">
                    المخزون
                </button>
                <button onclick="filterNotifications('orders')" 
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors filter-btn">
                    الطلبات
                </button>
                <button onclick="filterNotifications('debts')" 
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors filter-btn">
                    الديون
                </button>
                <button onclick="filterNotifications('system')" 
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors filter-btn">
                    النظام
                </button>
            </div>
        </div>

        <!-- قائمة الإشعارات -->
        <div class="space-y-4" id="notifications-list">
            @forelse($notifications as $notification)
                <div class="notification-item p-4 rounded-lg border {{ $notification->read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900' }} 
                            {{ $notification->category }} {{ $notification->read ? 'opacity-75' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold {{ $notification->read ? 'text-gray-900 dark:text-white' : 'text-blue-900 dark:text-blue-100' }}">
                                {{ $notification->title }}
                            </h3>
                            <p class="mt-2 {{ $notification->read ? 'text-gray-600 dark:text-gray-300' : 'text-blue-700 dark:text-blue-300' }}">
                                {{ $notification->message }}
                            </p>
                            <div class="mt-2 flex items-center gap-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if(!$notification->read)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            تحديد كمقروء
                                        </button>
                                    </form>
                                @endif

                                @if($notification->type === 'new_order' && !$notification->read)
                                    <div class="mt-2 flex gap-2">
                                        <form action="{{ route('orders.approve', json_decode($notification->data)->order_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                                                قبول
                                            </button>
                                        </form>
                                        <form action="{{ route('orders.reject', json_decode($notification->data)->order_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                                                رفض
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- أيقونة حسب نوع الإشعار -->
                            @switch($notification->category)
                                @case('inventory')
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    @break
                                @case('orders')
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    @break
                                @case('debts')
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                            @endswitch
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                    لا توجد إشعارات
                </div>
            @endforelse
        </div>

        <!-- ترقيم الصفحات -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
// إضافة ملف الصوت
const notificationSound = new Audio('/sounds/notification.mp3');

function filterNotifications(category) {
    // تحديث الأزرار النشطة
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('bg-blue-500', 'text-white');

    // إخفاء/إظهار الإشعارات
    document.querySelectorAll('.notification-item').forEach(item => {
        if (category === 'all' || item.classList.contains(category)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// تشغيل الصوت عند وجود إشعار جديد مع تنبيه صوتي
document.addEventListener('DOMContentLoaded', function() {
    const notifications = @json($notifications);
    notifications.forEach(notification => {
        if (notification.data && JSON.parse(notification.data).has_sound && !notification.read) {
            notificationSound.play();
        }
    });
});
</script>
@endpush
@endsection
