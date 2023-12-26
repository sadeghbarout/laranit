<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('pageTitle')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite([
        'resources/js/app.js'
    ])

    @stack('styles')
    <script type="text/javascript">
        if(top.location != window.location) {
            window.location = '/error';
        }
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed" style="overflow-x: hidden;">

<div id="adminLoading" class="w-100 bg-white top-0 d-flex align-items-center justify-content-center" style="position: fixed; height: 100vh;z-index: 9999;">
    <div class="spinner-border text-primary"></div>
</div>

<div class="wrapper" id="vueAppDiv"></div>

<script src="/js/adminlte.js?v=14"></script>

</body>
</html>

@stack('scripts')
