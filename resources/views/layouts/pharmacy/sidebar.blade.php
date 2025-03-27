<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-lg">
    <div class="app-brand demo py-3 px-4">
        <a href="{{ route('warehouse.dashboard') }}" class="app-brand-link d-flex align-items-center">
            <span class="app-brand-logo demo me-2">
                <img src="{{ asset('warehouse/img/logo.svg') }}" alt="PharmaLink" class="img-fluid" style="max-height: 40px;">
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="fas fa-times fa-lg text-dark"></i>
        </a>
    </div>

    <div class="menu-divider mt-0 border-top"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-3">
        <li class="menu-item ">
            <a href="{{ route('pharmacy.warehouses.index') }}" class="menu-link">
                <i class="menu-icon fas fa-store"></i>
                <div class="text-truncate">المستودعات</div>
            </a>
        </li>

        <!-- الطلبيات (مع سهم) -->
        <li class="menu-item {{ Route::is('warehouse.orders.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-shopping-cart"></i>
                <div class="text-truncate">الطلبيات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item ">
                    <a href="{{ route('pharmacy.cart.show') }}" class="menu-link">
                        <div class="text-truncate">السلة</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('warehouse.orders.index') ? 'active' : '' }}">
                    <a href="{{ route('pharmacy.orders.index') }}" class="menu-link">
                        <div class="text-truncate">الطلبيات </div>
                    </a>
                </li>

            </ul>
        </li>




        <!-- المالية (مع سهم) -->
        <li class="menu-item {{ Route::is('warehouse.cash.*') || Route::is('warehouse.expenses.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-money-bill-wave"></i>
                <div class="text-truncate">المالية</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('warehouse.cash.index') ? 'active' : '' }}">
                    <a href="{{ route('pharmacy.balance') }}" class="menu-link">
                        <div class="text-truncate">المدفوعات</div>
                    </a>
                </li>

            </ul>
        </li>

        <!-- الإعدادات (مع سهم) -->
        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon fas fa-cog"></i>
                <div class="text-truncate">الإعدادات</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('warehouse.settings.account') ? 'active' : '' }}">
                    <a href="{{ route('pharmacy.settings.account') }}" class="menu-link">
                        <div class="text-truncate">الحساب</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('pharmacy.settings.cities') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Cities">المدن المخدومة</div>
                    </a>
                </li>

            </ul>
        </li>

        <!-- الإشعارات (بدون سهم) -->
        <li class="menu-item ">
            <a href="" class="menu-link">
                <i class="menu-icon fas fa-bell"></i>
                <div class="text-truncate">الإشعارات</div>
                <span class="badge rounded-pill bg-danger ms-auto">3</span>
            </a>
        </li>

        <!-- تسجيل الخروج (بدون سهم) -->
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon fas fa-sign-out-alt"></i>
                <div class="text-truncate">تسجيل الخروج</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>

<style>
    /* الأساسيات */
    .bg-menu-theme {
        background-color: #f8f9fa;
        width: 260px;
        height: 100vh;
        position: fixed;
        top: 0;
        right: 0;
        overflow-y: auto;
        transition: width 0.3s ease;
    }

    .dark .bg-menu-theme {
        background-color: #1f2937;
    }

    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* العلامة التجارية */
    .app-brand {
        border-bottom: 1px solid #e5e7eb;
    }

    .dark .app-brand {
        border-bottom-color: #4b5563;
    }

    .app-brand-text {
        color: #343a40;
    }

    .dark .app-brand-text {
        color: #e5e7eb;
    }

    /* القائمة */
    .menu-inner {
        padding: 0.5rem 0;
    }

    .menu-item {
        margin: 0.25rem 0;
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: #495057;
        transition: all 0.3s ease;
        border-radius: 8px;
        margin: 0 0.5rem;
    }

    .dark .menu-link {
        color: #d1d5db;
    }

    .menu-link:hover {
        background-color: #e9ecef;
        color: #0288d1;
    }

    .dark .menu-link:hover {
        background-color: #374151;
        color: #4fc3f7;
    }

    .menu-item.active .menu-link {
        background-color: #0288d1;
        color: #fff;
    }

    .dark .menu-item.active .menu-link {
        background-color: #4fc3f7;
        color: #1f2937;
    }

    .menu-icon {
        margin-left: 1rem;
        font-size: 1.2rem;
        width: 1.5rem;
        text-align: center;
    }

    .toggle-icon {
        font-size: 0.9rem;
        transition: transform 0.3s ease;
    }

    .menu-item.open .toggle-icon {
        transform: rotate(180deg);
    }

    /* القوائم الفرعية */
    .menu-sub {
        display: none;
        padding-right: 2rem;
    }

    .menu-item.open .menu-sub {
        display: block;
    }

    .menu-sub .menu-link {
        padding: 0.5rem 1.5rem;
        font-size: 0.95rem;
    }

    /* البادج */
    .badge {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* زر الإغلاق */
    .layout-menu-toggle {
        color: #343a40;
    }

    .dark .layout-menu-toggle {
        color: #e5e7eb;
    }

    /* الاستجابة */
    @media (max-width: 1199px) {
        .bg-menu-theme {
            width: 0;
            overflow: hidden;
        }

        .bg-menu-theme.active {
            width: 260px;
        }
    }
</style>
