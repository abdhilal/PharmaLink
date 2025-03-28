<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            تفاصيل نقاط الولاء - {{ $loyalty->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Customer Info -->
                        <div class="p-4 border rounded-lg">
                            <h3 class="mb-4 text-lg font-semibold">معلومات الزبون</h3>
                            <div class="space-y-3">
                                <p><span class="font-semibold">الاسم:</span> {{ $loyalty->user->name }}</p>
                                <p><span class="font-semibold">البريد الإلكتروني:</span> {{ $loyalty->user->email }}</p>
                                <p><span class="font-semibold">النقاط الحالية:</span> {{ $loyalty->points }}</p>
                                <p><span class="font-semibold">إجمالي المشتريات:</span> {{ number_format($loyalty->total_spent, 2) }} ريال</p>
                                <p>
                                    <span class="font-semibold">المستوى:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $loyalty->tier === 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $loyalty->tier === 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $loyalty->tier === 'bronze' ? 'bg-amber-100 text-amber-800' : '' }}">
                                        {{ $loyalty->tier === 'gold' ? 'ذهبي' : '' }}
                                        {{ $loyalty->tier === 'silver' ? 'فضي' : '' }}
                                        {{ $loyalty->tier === 'bronze' ? 'برونزي' : '' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Add Points Form -->
                        <div class="p-4 border rounded-lg">
                            <h3 class="mb-4 text-lg font-semibold">إضافة نقاط</h3>
                            <form action="{{ route('loyalty.add-points', $loyalty->user) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="amount" class="block text-sm font-medium text-gray-700">قيمة المشتريات</label>
                                    <div class="mt-1">
                                        <input type="number" name="amount" id="amount" step="0.01" min="0"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">سيتم إضافة 10% من قيمة المشتريات كنقاط.</p>
                                </div>
                                <button type="submit"
                                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    إضافة النقاط
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
