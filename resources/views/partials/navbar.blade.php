{{-- resources/views/partials/navbar.blade.php --}}
@php include resource_path('views/partials/menu.php'); @endphp

@php
  $isActive = fn($rota) => $rota ? request()->routeIs($rota) : false;

  $canSee = function ($item) {
      if (!auth()->check()) return false;
      $usuario = auth()->user();
      if (isset($item['perm']) && !$usuario->can($item['perm'])) return false;
      if (isset($item['roles']) && !$usuario->hasAnyRole((array)$item['roles'])) return false;
      if (isset($item['requer_roles']) && !$usuario->hasAnyRole((array)$item['requer_roles'])) return false;
      return true;
  };

  $visibleChildren = fn($filhos) =>
      collect($filhos ?? [])->filter(fn($item) => $canSee($item))->values()->all();
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white border-b">
  <div class="px-4 h-14 flex items-center justify-between">
    <!-- Esquerda -->
    <div class="flex items-center gap-4">
      <!-- Botão hambúrguer -->
      <button @click="open = !open" class="md:hidden p-2 rounded hover:bg-gray-100">
        <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M6 18L18 6"/></svg>
      </button>

      <a href="{{ route('dashboard') }}" class="font-semibold">ADMS 2.0</a>

      <div class="hidden md:flex items-center gap-3">
        @foreach ($menuPrincipal as $item)
          @if (!$canSee($item)) @continue @endif

          @php $filhos = $visibleChildren($item['submenu'] ?? []); @endphp

          @if (count($filhos) > 0)
            <div x-data="{ sub: false }" class="relative">
              <button @click="sub = !sub" class="px-2 py-1 hover:bg-gray-100 rounded">
                {{ $item['rotulo'] }}
              </button>
              <div x-show="sub" x-cloak @click.outside="sub = false"
                   class="absolute mt-2 w-40 bg-white border rounded shadow-md z-20">
                @foreach ($filhos as $child)
                  <a href="{{ route($child['rota']) }}"
                     class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['rota']) ? 'font-semibold text-blue-600' : '' }}">
                    {{ $child['rotulo'] }}
                  </a>
                @endforeach
              </div>
            </div>
          @else
            <a href="{{ route($item['rota']) }}"
               class="px-2 py-1 hover:bg-gray-100 rounded {{ $isActive($item['rota']) ? 'font-semibold text-blue-600' : '' }}">
              {{ $item['rotulo'] }}
            </a>
          @endif
        @endforeach
      </div>
    </div>

    <!-- Direita -->
    <div class="hidden md:flex items-center gap-4">
      @foreach ($menuUsuario as $item)
        @if (!$canSee($item)) @continue @endif

        @php $filhos = $visibleChildren($item['submenu'] ?? []); @endphp

        @if (count($filhos) > 0)
          <div x-data="{ sub: false }" class="relative">
            <button @click="sub = !sub" class="px-2 py-1 hover:bg-gray-100 rounded">
              {{ $item['rotulo'] }}
            </button>
            <div x-show="sub" x-cloak @click.outside="sub = false"
                 class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-md z-20">
              @foreach ($filhos as $child)
                @if ($child['rota'] === 'logout')
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-100">
                      {{ $child['rotulo'] }}
                    </button>
                  </form>
                @else
                  <a href="{{ route($child['rota']) }}"
                     class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['rota']) ? 'font-semibold text-blue-600' : '' }}">
                    {{ $child['rotulo'] }}
                  </a>
                @endif
              @endforeach
            </div>
          </div>
        @else
          <a href="{{ route($item['rota']) }}"
             class="px-2 py-1 hover:bg-gray-100 rounded {{ $isActive($item['rota']) ? 'font-semibold text-blue-600' : '' }}">
            {{ $item['rotulo'] }}
          </a>
        @endif
      @endforeach
    </div>
  </div>

  <!-- Mobile -->
  <div x-show="open" x-cloak class="md:hidden border-t bg-white p-2 space-y-1">
    @foreach ($menuPrincipal as $item)
      @if (!$canSee($item)) @continue @endif

      @php $filhos = $visibleChildren($item['submenu'] ?? []); @endphp

      @if (count($filhos) > 0)
        <details>
          <summary class="px-3 py-2 cursor-pointer hover:bg-gray-100">{{ $item['rotulo'] }}</summary>
          <div class="pl-4">
            @foreach ($filhos as $child)
              <a href="{{ route($child['rota']) }}"
                 class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['rota']) ? 'font-semibold text-blue-600' : '' }}">
                {{ $child['rotulo'] }}
              </a>
            @endforeach
          </div>
        </details>
      @else
        <a href="{{ route($item['rota']) }}"
           class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($item['rota']) ? 'font-semibold text-blue-600' : '' }}">
          {{ $item['rotulo'] }}
        </a>
      @endif
    @endforeach

    <hr>

    @foreach ($menuUsuario as $item)
      @if (!$canSee($item)) @continue @endif
      @foreach ($visibleChildren($item['submenu'] ?? []) as $child)
        @if ($child['rota'] === 'logout')
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-100">{{ $child['rotulo'] }}</button>
          </form>
        @else
          <a href="{{ route($child['rota']) }}"
             class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['rota']) ? 'font-semibold text-blue-600' : '' }}">
            {{ $child['rotulo'] }}
          </a>
        @endif
      @endforeach
    @endforeach
  </div>
</nav>
