<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title','2FA-Laravel')</title>

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/core.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/icon-font.min.css')}}" />
        @stack('page-css')
        <link rel="stylesheet" type="text/css" href="{{asset('src/plugins/sweetalert2/sweetalert2.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/style.css')}}" />
        @stack('custom-css')
        <script type="text/javascript">
            const BASE_URL = '{{url("")}}/';
            const ASSET_URL = '{{asset("")}}';
        </script>
    </head>
    <body class="header-white sidebar-light">
        @include('layouts.pre-loader')

        @include('layouts.header')

        @include('layouts.sidebar')

        <div class="mobile-menu-overlay"></div>

        <div class="main-container">
            @yield('content')
        </div>
        @include('layouts.model');

        <script src="{{asset('vendors/scripts/core.js')}}"></script>
        <script src="{{asset('vendors/scripts/script.min.js')}}"></script>
        <script src="{{asset('vendors/scripts/process.js')}}"></script>
        <script src="{{asset('src/plugins/sweetalert2/sweetalert2.all.js')}}"></script>
        <script src="{{asset('vendors/scripts/bootstrap-modal.js')}}"></script>
        @stack('scripts')
    </body>
</html>
