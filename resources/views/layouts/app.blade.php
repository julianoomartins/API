<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name', 'ADMS 2.0'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap e Ícones --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    {{-- Seus assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body {
            background-color: #f4f6f9; /* fundo cinza padrão AdminLTE */
        }
        .main-header {
            background: #343a40; /* navbar escura */
            color: #fff;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }
        .main-sidebar {
            width: 220px;
            background: #343a40;
            color: #c2c7d0;
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            overflow-y: auto;
        }
        .content-wrapper {
            margin-left: 220px; /* espaço pro sidebar */
            padding: 1rem;
        }
        .brand-link {
            display: block;
            padding: 1rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-sidebar .nav-link {
            color: #c2c7d0;
            padding: .5rem 1rem;
            display: block;
        }
        .nav-sidebar .nav-link.active {
            background: #495057;
            color: #fff;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    {{-- Navbar superior --}}
    <header class="main-header">
        <span class="me-auto fw-bold">{{ config('app.name', 'ADMS 2.0') }}</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-danger">Sair</button>
        </form>
    </header>

    {{-- Sidebar --}}
    <aside class="main-sidebar">
        <a href="{{ route('dashboard') }}" class="brand-link">
            {{ config('app.name', 'ADMS 2.0') }}
        </a>
        <nav class="mt-2 nav-sidebar">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Usuários
            </a>
        </nav>
    </aside>

    {{-- Conteúdo principal --}}
    <main class="content-wrapper">
    {{-- Cabeçalho da página --}}
    <section class="content-header mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap bg-white border rounded shadow-sm p-3">

            {{-- Esquerda: título + breadcrumb --}}
            <div>
                <h1 class="h5 mb-1">
                    @hasSection('icon')
                        <i class="@yield('icon') text-primary me-1"></i>
                    @endif
                    @yield('title', 'Título')
                </h1>

                {{-- Breadcrumb automático ou custom --}}
                <nav aria-label="breadcrumb">
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @else
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Página')</li>
                        </ol>
                    @endif
                </nav>
            </div>

            {{-- Direita: botão de ação --}}
            <div class="mt-2 mt-sm-0">
                @hasSection('action')
                    @yield('action')
                @endif
            </div>
        </div>
    </section>

    {{-- Conteúdo principal dentro do card --}}
    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </section>
</main>


    @stack('scripts')
</body>
</html>
