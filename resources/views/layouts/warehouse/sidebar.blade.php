<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('warehouse.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <div><img src="{{ asset('warehouse/img/logo.svg') }}" alt="PharmaLink"></div>
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
        <li class="menu-item {{ request()->routeIs('warehouse.dashboard') ? 'active' : '' }}">
            <a href="{{ route('warehouse.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">لوحة التحكم</div>
            </a>
        </li>
     <!-- الإشعارات -->
     <li class="menu-item {{ request()->routeIs('warehouse.notifications') ? 'active' : '' }}">
        <a href="{{ route('warehouse.notifications') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-bell"></i>
            <div class="text-truncate" data-i18n="Notifications">الإشعارات</div>
            <span id="notification-count" class="badge rounded-pill bg-danger ms-auto" style="display: none;"></span>
        </a>
    </li>

        <!-- إدارة الأدوية -->
        <li class="menu-item {{ request()->routeIs('warehouse.medicines.*') ? 'active open' : '' }}">
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
                        <div class="text-truncate" data-i18n="Brochure">البروشور</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- الطلبيات -->
        <li
            class="menu-item {{ request()->routeIs('warehouse.orders.*') || request()->routeIs('warehouse.urgentorder.*') ? 'active open' : '' }}">
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
                        <div class="text-truncate" data-i18n="Manual Order">طلبية بيع مباشر</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.urgentorder.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Urgent Orders">الطلبيات العاجلة</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- الصيدليات -->
        <li class="menu-item {{ request()->routeIs('warehouse.pharmacies.*') ? 'active' : '' }}">
            <a href="{{ route('warehouse.pharmacies.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Pharmacies">الصيدليات</div>
            </a>
        </li>

        <!-- الموردين -->
        <li class="menu-item {{ request()->routeIs('warehouse.suppliers.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="fas fa-truck me-2"></i>
                <div class="text-truncate" data-i18n="Suppliers">الموردين</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('warehouse.suppliers.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="View Suppliers">عرض الموردين</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.suppliers.create') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Add Supplier">إضافة مورد</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- المالية -->
        <li
            class="menu-item {{ request()->routeIs('warehouse.cash.*') || request()->routeIs('warehouse.expenses.*') || request()->routeIs('warehouse.employees.*') || request()->routeIs('warehouse.staff.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div class="text-truncate" data-i18n="Payments">المالية</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('warehouse.cash.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Cash">الصندوق</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.expenses.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Expenses">المصاريف</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.employees.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Employees">الموظفون</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('warehouse.staff.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Staff">المندوبون</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- الإعدادات -->
        <li class="menu-item {{ request()->routeIs('warehouse.settings.*') ? 'active open' : '' }}">
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


        <!-- التقارير -->
        <li class="menu-item {{ request()->routeIs('warehouse.financial_report') ? 'active' : '' }}">
            <a href="{{ route('warehouse.financial_report') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                <div class="text-truncate" data-i18n="Reports">التقارير</div>
            </a>
        </li>

        <!-- تسجيل الخروج -->
        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-log-out"></i>
                <div class="text-truncate" data-i18n="Logout">تسجيل الخروج</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</aside>


<script>
    // إعداد Pusher
    Pusher.logToConsole = true;
    var pusher = new Pusher('df94e3c2097e72396c49', {
        cluster: 'eu',
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    });

    var channel = pusher.subscribe('private-notifications.{{ Auth::user()->warehouse->id }}');
    channel.bind('notification.received', function(data) {
        var notificationsList = document.querySelector('#notifications ul');
        var newNotification = document.createElement('a');
        newNotification.href = '#';
        newNotification.className =
            'list-group-item mb-2 unread text-decoration-none text-dark d-flex justify-content-between align-items-center';
        newNotification.onclick = function() {
            markNotificationAsRead('/notifications/' + data.notification_id + '/read', '/warehouse/suppliers/' + data.order_id);
        };
        newNotification.innerHTML = `
            <span class="badge bg-primary">جديد</span>
            <div class="text-right">
                ${data.message}
                <br>
                <small class="text-muted">منذ لحظات</small>
            </div>
        `;

        if (notificationsList) {
            notificationsList.prepend(newNotification);
        } else {
            document.getElementById('notifications').innerHTML = `
                <ul class="list-group">
                    ${newNotification.outerHTML}
                </ul>
            `;
        }

        // تحديث عدد الإشعارات في الأيقونة الحمراء
        var notificationCountElement = document.getElementById('notification-count');
        var notificationCount = data.notificationCount || 0; // تأكد من أن القيمة ليست undefined

        if (notificationCount > 0) {
            notificationCountElement.textContent = notificationCount; // تحديث العدد
            notificationCountElement.style.display = 'inline-block'; // إظهار الأيقونة
        } else {
            notificationCountElement.textContent = ''; // إفراغ النص
            notificationCountElement.style.display = 'none'; // إخفاء الأيقونة
        }
    });

    function markNotificationAsRead(markAsReadUrl, redirectUrl) {
        event.preventDefault();
        fetch(markAsReadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = redirectUrl;
                } else {
                    alert('فشل في تحديث حالة الإشعار: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ: ' + error.message);
            });
    }
</script>
