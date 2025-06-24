<html lang="{{ app()->getLocale() }}" class="loading" data-textdirection="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="/images/favicon.png"/>
    <title>@yield('pageTitle')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @vite([
        'resources/js/app.js',
    ])

    <link rel="stylesheet" href="/css/custom.css?v=4">
    {{--<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>--}}

    <script type="text/javascript">
        if(top.location != window.location) {
            window.location = '/error';
        }
    </script>
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="vertical-layout vertical-menu-modern semi-dark-layout content-left-sidebar chat-application navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="content-left-sidebar" data-layout="semi-dark-layout">

<div id="adminLoading" class="w-100 bg-white top-0 d-flex align-items-center justify-content-center" style="position: fixed; height: 100vh;z-index: 9999;">
    <div class="spinner-border" style="color: #7367F0;"></div>
</div>

<div id="loading" class="w-100 top-0 d-none align-items-center justify-content-center" style="position: fixed; height: 100vh;z-index: 999999999999999999999999999;background-color: rgba(0,0,0,0.5);">
    <div class="spinner-border text-primary"></div>
</div>

<div id="vueAppDiv"></div>

</body>
</html>


{{--<script src="{{$prefixHtml}}libs/ckeditor_4.9.2/ckeditor.js"></script>--}}
@stack('vue')
<script src="/js/vuexy.js"></script>
<script>
    $(document).ready(function(){
        $('.dropdown-toggle').dropdown()
    });
</script>
@stack('scripts')
