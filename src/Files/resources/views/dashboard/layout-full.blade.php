<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('pageTitle')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{$prefixHtml.mix('css/app.css')}}?v=2">
    <link rel="stylesheet" href="/css/custom.css">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div id="vueAppDiv">

        <div  style="height: fit-content;min-height:100vh !important">
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                    <router-view></router-view>
                </div>

            </section>
        </div>

        <div class="control-sidebar-bg"></div>
    </div>
</body>
</html>


{{--<script src="{{$prefixHtml}}libs/ckeditor_4.9.2/ckeditor.js"></script>--}}
@stack('vue')
<script src="{{$prefixHtml.mix('js/app.js')}}?v=2.98"></script>


@stack('scripts')
