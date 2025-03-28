<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            نظام الولاء
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="mb-4 text-lg font-semibold">مستويات الولاء:</h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Bronze Level -->
                            <div class="p-4 border rounded-lg bg-amber-50">
                                <div class="flex items-center mb-2">
                                    <span class="w-8 h-8 mr-2 text-amber-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    <h4 class="text-lg font-semibold text-amber-700">برونزي</h4>
                                </div>
                                <p class="text-amber-600">0 - 499 نقطة</p>
                                <p class="mt-2 text-sm text-amber-600">خصم 5% على جميع المشتريات</p>
                            </div>

                            <!-- Silver Level -->
                            <div class="p-4 border rounded-lg bg-slate-50">
                                <div class="flex items-center mb-2">
                                    <span class="w-8 h-8 mr-2 text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                        </svg>
                                    </span>
                                    <h4 class="text-lg font-semibold text-slate-700">فضي</h4>
                                </div>
                                <p class="text-slate-600">500 - 999 نقطة</p>
                                <p class="mt-2 text-sm text-slate-600">خصم 10% على جميع المشتريات</p>
                            </div>

                            <!-- Gold Level -->
                            <div class="p-4 border rounded-lg bg-yellow-50">
                                <div class="flex items-center mb-2">
                                    <span class="w-8 h-8 mr-2 text-yellow-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    <h4 class="text-lg font-semibold text-yellow-700">ذهبي</h4>
                                </div>
                                <p class="text-yellow-600">1000+ نقطة</p>
                                <p class="mt-2 text-sm text-yellow-600">خصم 15% على جميع المشتريات</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="mb-4 text-lg font-semibold">الزبائن المميزين:</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النقاط</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستوى</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجمالي المشتريات</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($loyaltyPoints as $loyalty)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loyalty->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loyalty->points }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $loyalty->tier === 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $loyalty->tier === 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $loyalty->tier === 'bronze' ? 'bg-amber-100 text-amber-800' : '' }}">
                                                {{ $loyalty->tier === 'gold' ? 'ذهبي' : '' }}
                                                {{ $loyalty->tier === 'silver' ? 'فضي' : '' }}
                                                {{ $loyalty->tier === 'bronze' ? 'برونزي' : '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($loyalty->total_spent, 2) }} ريال</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('loyalty.show', $loyalty->user) }}" class="text-indigo-600 hover:text-indigo-900">عرض التفاصيل</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $loyaltyPoints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
