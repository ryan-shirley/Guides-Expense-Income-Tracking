<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title') - IGG</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> <!-- Argon font -->

    <!-- Data Tables -->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('css/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />

    <!-- Favicon -->
    <link type="image/png" rel="icon" sizes="32x32" href="{{ asset('favicon/favicon-32.png') }}">
    <link type="image/png" rel="icon" sizes="57x57" href="{{ asset('favicon/favicon-57.png') }}">
    <link type="image/png" rel="icon" sizes="76x76" href="{{ asset('favicon/favicon-76.png') }}">
    <link type="image/png" rel="icon" sizes="120x120" href="{{ asset('favicon/favicon-120.png') }}">
    <link type="image/png"rel="icon" sizes="128x128" href="{{ asset('favicon/favicon-128.png') }}">
    <link type="image/png" rel="icon" sizes="152x152" href="{{ asset('favicon/favicon-152.png') }}">
    <link type="image/png" rel="icon" sizes="167x167" href="{{ asset('favicon/favicon-167.png') }}">
    <link type="image/png" rel="icon" sizes="180x180" href="{{ asset('favicon/favicon-180.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/favicon-180.png') }}">
    <link type="image/png" rel="icon" sizes="192x192" href="{{ asset('favicon/favicon-192.png') }}">
    <link type="image/png" rel="icon" sizes="196x196" href="{{ asset('favicon/favicon-196.png') }}">
    <link type="image/png" rel="icon" sizes="228x228" href="{{ asset('favicon/favicon-228.png') }}">
</head>
<body class="bg-default">
    <div id="app">
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
                <div class="container px-4">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Guides
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar-collapse-main">
                        <!-- Collapse header -->
                        <div class="navbar-collapse-header d-md-none">
                            <div class="row">
                                <div class="col-6 collapse-brand">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ asset('images/igg-logo.png') }}" />
                                    </a>
                                </div>
                                <div class="col-6 collapse-close">
                                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                                        <span></span>
                                        <span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Navbar items -->
                        <ul class="navbar-nav ml-auto">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link nav-link-icon" href="{{ route('register') }}">
                                        <i class="ni ni-circle-08"></i>
                                        <span class="nav-link-inner--text">{{ __('Register') }}</span>
                                    </a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link nav-link-icon" href="{{ route('login') }}">
                                            <i class="ni ni-key-25"></i>
                                            <span class="nav-link-inner--text">{{ __('Login') }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Header -->
            <div class="header bg-gradient-primary py-7 py-lg-8">
                <div class="container">
                    <div class="header-body text-center mb-7">
                        <div class="row justify-content-center">
                            <div class="col-lg-5 col-md-6">
                                <h1 class="text-white">@yield('title')</h1>
                                <p class="text-lead text-light">@yield('sub-title')</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator separator-bottom separator-skew zindex-100">
                    <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                    </svg>
                </div>
            </div>
            <!-- Page content -->
            <div class="container mt--8 pb-5">
                @yield('content')
            </div>
            <footer class="py-5">
                <div class="container">
                    <div class="row align-items-center justify-content">
                        <div class="col">
                            <div class="copyright text-center text-muted">
                                &copy; {{ now()->year }} - Built by <a href="https://ryanshirley.ie" class="font-weight-bold ml-1" target="_blank">Ryan Shirley</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- /.Core   -->
    </div>
    <!-- /.App -->
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
