<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative p-1 text-gray-600 hover:text-gray-700 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <span id="notifications-counter" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 w-80 mt-2 overflow-hidden origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
        <div id="notifications-list" class="py-1">
            @forelse(auth()->user()->notifications as $notification)
                <div class="px-4 py-2 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                    <p class="text-sm text-gray-900">{{ $notification->data['message'] }}</p>
                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                    @if(!$notification->read_at)
                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">تحديد كمقروء</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="px-4 py-2">
                    <p class="text-sm text-gray-500">لا توجد إشعارات</p>
                </div>
            @endforelse
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="px-4 py-2 bg-gray-50 text-right">
                <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">تحديد الكل كمقروء</button>
                </form>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // تهيئة userId للاستخدام في bootstrap.js
    window.userId = {{ auth()->id() }};
</script>
@endpush
