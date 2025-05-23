<!doctype html>
<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', 'Default Title')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('warehouse/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Boxicons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('warehouse/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('warehouse/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('warehouse/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('warehouse/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('warehouse/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('warehouse/js/config.js') }}"></script>
    <!-- إضافة CSS و JS لـ Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<!-- إضافة CSS و JavaScript لـ Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<meta name="csrf-token" content="{{ csrf_token() }}"> <!-- إضافة CSRF Token -->
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
</head>

<style>
.table th, .table td {
    text-align: center;
    vertical-align: middle;
}
.btn-sm {
    padding: 0.35rem 0.75rem;
}
.card {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}
.bg-primary {
    background-color: #3B82F6 !important;
}</style>

  <body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.warehouse.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.warehouse.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                        @yield('scripts')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.warehouse.footer')
                    <!-- / Footer -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
<!-- Core JS -->
<script src="{{ asset('warehouse/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('warehouse/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('warehouse/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('warehouse/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('warehouse/vendor/js/menu.js') }}"></script>

<!-- Vendors JS -->
<script src="{{ asset('warehouse/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('warehouse/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('warehouse/js/dashboards-analytics.js') }}"></script>

<!-- Place this tag before closing body tag for github widget button. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>
</html>

