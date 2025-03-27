<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('warehouse.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <div class=""><img src="{{asset('warehouse/img/logo.svg')}}" alt="PharmaLink"></div>

            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2"></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- لوحة التحكم -->
        <li class="menu-item active">
            <a href="{{ route('warehouse.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">لوحة التحكم</div>
            </a>
        </li>

        <!-- إدارة الأدوية -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-capsule"></i>
                <div class="text-truncate" data-i18n="Medicines">الأدوية</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('warehouse.medicines.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="View Medicines">عرض الأدوية</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.medicines.create') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Add Medicine">إضافة دواء</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.medicines.brochure') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Add Medicine">البروشور</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- الطلبيات -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div class="text-truncate" data-i18n="Orders">الطلبيات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('warehouse.orders.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="View Orders">عرض الطلبيات</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.orders.create_manual') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="View Orders">طلبية بيع مباشر</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.urgentorder.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="View Orders">  الطبيات العاجلة</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- الصيدليات -->
        <li class="menu-item">
            <a href="{{ route('warehouse.pharmacies.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Pharmacies">الصيدليات</div>
            </a>
        </li>

        <!-- الموردين (تمت الإضافة هنا) -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-car"></i>
                <div class="text-truncate" data-i18n="Suppliers">الموردين</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{route('warehouse.suppliers.index')}}" class="menu-link">
                        <div class="text-truncate" data-i18n="View Suppliers">عرض الموردين</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('warehouse.suppliers.create')}}" class="menu-link">
                        <div class="text-truncate" data-i18n="Add Supplier">إضافة مورد</div>
                    </a>
                </li>
            </ul>
        </li>

            <!-- روابط أخرى -->


        <li class="menu-item">

                <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div class="text-truncate" data-i18n="Payments">المالية</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('warehouse.cash.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Account">الصندوق</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.expenses.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Cities">المصاريف</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-link" href="{{ route('warehouse.employees.index') }}"><div class="text-truncate" data-i18n="Cities">الموظفون</div></a>
                </li>
            </ul>
        </li>

        <!-- إعدادات الحساب -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Settings">الإعدادات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('warehouse.settings.account') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Account">الحساب</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.settings.cities') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Cities">المدن المخدومة</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- الإشعارات -->
        <li class="menu-item">
            <a href="{{ route('warehouse.notifications') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bell"></i>
                <div class="text-truncate" data-i18n="Notifications">الإشعارات</div>
                <span class="badge rounded-pill bg-danger ms-auto">3</span>
            </a>
        </li>

        <!-- التقارير -->
        <li class="menu-item">
            <a href="{{ route('warehouse.financial_report') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                <div class="text-truncate" data-i18n="Reports">التقارير</div>
            </a>
        </li>

        <!-- تسجيل الخروج -->
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div class="text-truncate" data-i18n="Logout">تسجيل الخروج</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>
