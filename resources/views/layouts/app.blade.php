<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('app.name', 'ADMS 2.0') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased" x-data>
  {{-- Navbar fixa no topo --}}
  @include('layouts.partials.navbar')

  {{-- Sidebar fixa à esquerda --}}
  @include('layouts.partials.sidebar')

  {{-- Conteúdo deslocado via CSS (margin-left) --}}
  <main class="min-w-0">
    {{-- Header opcional --}}
    @if (View::hasSection('header'))
      <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          @yield('header')
        </div>
      </header>
    @endif

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
      @yield('content')
    </div>
  </main>
</body>

</html>
