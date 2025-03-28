<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PharmaLink') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* تنسيقات الوضع الليلي */
        .dark {
            color-scheme: dark;
        }
        
        .dark body {
            background-color: #1f2937;
            color: #f9fafb;
        }
        
        .dark .bg-white {
            background-color: #1f2937;
        }
        
        .dark .text-gray-900 {
            color: #f9fafb;
        }
        
        .dark .border-gray-200 {
            border-color: #374151;
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #1d4ed8;
            --accent-color: #3b82f6;
            --background-color: #f8fafc;
            --text-color: #1e293b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* تنسيق القائمة العلوية */
        nav {
            background: linear-gradient(to left, var(--primary-color), var(--secondary-color));
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .nav-brand:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        /* تنسيق المحتوى */
        .content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-top: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* تنسيق الأزرار */
        .btn {
            background-color: var(--accent-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* تنسيق الجداول */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
        }

        th, td {
            padding: 1rem;
            text-align: right;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--text-color);
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        /* تنسيق التنبيهات */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            nav .container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            nav a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
    @stack('scripts')
</head>
<body class="h-full font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- التنقل -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                PharmaLink
                            </a>
                        </div>

                        <!-- القائمة الرئيسية -->
                        @auth
                            @if (Auth::user()->warehouse)
                                <a href="{{ route('warehouse.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">لوحة التحكم</a>
                                <a href="{{ route('warehouse.medicines.index') }}" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">الأدوية</a>
                                <a href="{{ route('warehouse.payments.index') }}" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">المدفوعات</a>
                            @else
                                <a href="{{ route('pharmacy.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">لوحة التحكم</a>
                                <a href="{{ route('pharmacy.cart.show') }}" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">السلة</a>
                                <a href="{{ route('pharmacy.balance') }}" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">المدفوعات</a>
                            @endif
                        @endauth
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- زر الوضع الليلي -->
                        <button @click="darkMode = !darkMode" 
                                class="p-2.5 rounded-full bg-red-500 hover:bg-red-600 text-white transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707" />
                            </svg>
                        </button>

                        <!-- الإشعارات -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="p-2 bg-white dark:bg-gray-700 rounded-full shadow-sm hover:shadow-md transition-all duration-300 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-700"></span>
                            </button>

                            <!-- قائمة الإشعارات -->
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-3 w-96 rounded-lg bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black ring-opacity-5 z-50">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإشعارات</h3>
                                    <div class="space-y-3">
                                        @foreach(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="flex items-start gap-4 p-3 {{ $notification->read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900' }} rounded-lg border {{ $notification->read ? 'border-gray-200 dark:border-gray-600' : 'border-blue-200 dark:border-blue-800' }}">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium {{ $notification->read ? 'text-gray-900 dark:text-white' : 'text-blue-900 dark:text-blue-100' }}">
                                                    {{ $notification->title }}
                                                </p>
                                                <p class="text-sm {{ $notification->read ? 'text-gray-500 dark:text-gray-400' : 'text-blue-700 dark:text-blue-300' }} mt-1">
                                                    {{ $notification->message }}
                                                </p>
                                                <div class="flex items-center justify-between mt-2">
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                    @if(!$notification->read)
                                                        <button class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                            تحديد كمقروء
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('notifications.index') }}" class="block text-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 mt-4 py-2 border-t border-gray-100 dark:border-gray-700">
                                        عرض كل الإشعارات
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- أيقونة الملف الشخصي -->
                        <img class="h-8 w-8 rounded-full ring-2 ring-gray-200 dark:ring-gray-700" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">

                        @auth
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                تسجيل الخروج
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- المحتوى الرئيسي -->
        <main class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
