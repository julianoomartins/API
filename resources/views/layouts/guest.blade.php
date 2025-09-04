<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Estilos e scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Espaço para estilos extras -->
    @stack('styles')
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <section class="min-h-screen flex flex-col items-center justify-center p-6">

        <!-- Logo ou título -->
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <!-- Área de conteúdo -->
        <div class="w-full sm:max-w-md bg-white shadow-md rounded-xl p-6">
            {{ $slot }}
        </div>
    </section>

    <!-- Espaço para scripts extras -->
    @stack('scripts')
</body>

</html>
