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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-html5-1.6.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-html5-1.6.1/datatables.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.3.0/jszip.min.js" defer></script>

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
<body>
    <div id="app">
        <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
            <div class="container-fluid">
                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Brand -->
                <a class="navbar-brand pt-0" href="{{ url('/home') }}">
                    <img src="{{ asset('images/igg-logo.png') }}" class="navbar-brand-img" alt="IGG Logo">
                </a>
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <!-- Collapse header -->
                    <div class="navbar-collapse-header d-md-none">
                        <div class="row">
                            <div class="col-6 collapse-brand">
                                <a href="{{ url('/home') }}">
                                    <img src="{{ asset('images/igg-logo.png') }}"alt="IGG Logo">
                                </a>
                            </div>
                            <div class="col-6 collapse-close">
                                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Navigation -->
                    <ul class="navbar-nav">
                        @if(Auth::user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.home' ? 'active' : '' }}" href="{{ route('admin.home') }}">
                                    <i class="ni ni-tv-2 text-primary"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.payments.index' ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                                    <i class="fas fa-receipt text-danger"></i> Payments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.incomes.index' ? 'active' : '' }}" href="{{ route('admin.incomes.index') }}">
                                    <i class="fas fa-euro-sign text-success"></i> Income
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.bank-transactions.index' ? 'active' : '' }}" href="{{ route('admin.bank-transactions.index') }}">
                                    <i class="fas fa-piggy-bank text-info"></i> Bank Transactions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.events.index' ? 'active' : '' }}" href="{{ route('admin.events.index') }}">
                                    <i class="fas fa-map-signs text-default"></i> Events
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.payments.toPayBack' ? 'active' : '' }}" href="{{ route('admin.payments.toPayBack') }}">
                                    <i class="fas fa-users text-warning"></i> To Pay Back
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.users' ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                <i class="fas fa-user-plus text-primary"></i> Approve Accounts
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'leader.home' ? 'active' : '' }}" href="{{ route('leader.home') }}">
                                    <i class="ni ni-tv-2 text-primary"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'leader.chatbot' ? 'active' : '' }}" href="{{ route('leader.chatbot') }}">
                                    <i class="fas fa-receipt text-warning"></i> Chatbot
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Side Navigation -->
        <main class="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
                <div class="container-fluid">
                    <!-- Brand -->
                    <p class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block">@yield('page-title')</p>
                    <!-- User -->
                    <ul class="navbar-nav align-items-center d-none d-md-flex">
                        <li class="nav-item dropdown">
                            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="media align-items-center">
                                    <span class="avatar avatar-sm rounded-circle">
                                        <img alt="Image placeholder" src="{{ asset('images/profile.png') }}">
                                    </span>
                                    <div class="media-body ml-2 d-none d-lg-block">
                                        <span class="mb-0 text-sm  font-weight-bold">{{ Auth::user()->name }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                                <!-- <a href="./examples/profile.html" class="dropdown-item">
                                    <i class="ni ni-single-02"></i>
                                    <span>My profile*</span>
                                </a> -->
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="ni ni-user-run"></i>
                                    <span>{{ __('Logout') }}</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->

            <!-- Header -->
            <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
                <div class="container-fluid">
                    <div class="header-body">
                        @yield('header')
                    </div>
                    <!-- /.Header Body -->

                </div>
                <!-- /.Container Fluid -->
            </div>
            <!-- /.Header -->

            <!-- Main Content -->
            <div class="container-fluid mt--7">
                @yield('content')

                <!-- Footer -->
                <footer class="py-5 footer">
                    <div class="container">
                        <div class="row align-items-center justify-content">
                            <div class="col">
                                <div class="copyright text-center text-muted">
                                    &copy; {{ now()->year }} - Built by <a href="https://ryanshirley.ie" class="font-weight-bold" target="_blank">Ryan Shirley</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- /.Footer -->
            </div>
            <!-- /.Main Content -->
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    @yield('scripts')

{{--    @foreach (['error', 'warning', 'success', 'info'] as $msg)--}}
{{--        @if(Session::has('alert-' . $msg))--}}

{{--            <!-- Notification -->--}}
{{--            <script type="text/javascript">--}}
{{--                Swal.fire({--}}
{{--                    title: "{{ Session::get('alert-' . $msg) }}",--}}
{{--                    type: "{{ $msg }}",--}}
{{--                    showButton: true,--}}
{{--                    confirmButtonText: 'close',--}}
{{--                    buttonsStyling: false,--}}
{{--                    customClass: {--}}
{{--                        confirmButton: 'btn btn-default',--}}
{{--                    },--}}
{{--                    timer: 3000--}}
{{--                })--}}
{{--            </script>--}}
{{--            <!-- /.Notification -->--}}
{{--        @endif--}}
{{--    @endforeach--}}
</body>
</html>
