<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name') . ' — Dashboard')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        .content-header h1 {
            font-size: 1.1rem;
            line-height: 1.2;
            margin: 0;
        }

        .navbar .dropdown-menu .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #212529 !important;
        }

        .navbar .dropdown-menu .dropdown-item.active,
        .navbar .dropdown-menu .dropdown-item:active,
        .navbar .dropdown-submenu>.dropdown-item.active {
            background-color: transparent !important;
            color: #212529 !important;
        }

        .navbar .dropdown-menu .dropdown-item:focus {
            background-color: transparent !important;
        }

        .navbar .dropdown-menu .dropdown-item:focus-visible {
            background-color: #e9ecef !important;
            outline: none !important;
        }

        .content-header .breadcrumb {
            font-size: 12px;
        }

        .content-header .breadcrumb+div>.btn {
            margin-left: .5rem;
        }
    </style>

    @stack('styles')
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        @include('partials.navbar')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 d-flex align-items-center">
                            <h1 class="mb-0">@yield('title')</h1>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                <ol class="breadcrumb mb-0 mr-2">
                                    <x-breadcrumbs :title="trim($__env->yieldContent('title'))" />
                                </ol>

                                @if (View::hasSection('header_actions'))
                                    <div class="mb-0">
                                        @yield('header_actions')
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Flash messages (sucesso, erro etc.) --}}
            @if (session('success'))
                <div class="container-fluid">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer small">
            <div class="float-right d-none d-sm-inline">v1.0</div>
            <strong>&copy; {{ date('Y') }} {{ config('app.name', 'API') }}.</strong> Todos os direitos reservados.
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')
</body>

</html>
