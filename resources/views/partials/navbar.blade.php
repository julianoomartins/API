{{-- resources/views/partials/navbar.blade.php --}}
@php include resource_path('views/partials/menu.php'); @endphp


@php
  $isActive = fn($route) => $route ? request()->routeIs($route) : false;

  $canSee = function ($item) {
      if (!auth()->check()) return false;
      $u = auth()->user();
      if (isset($item['perm']) && !$u->can($item['perm'])) return false;
      if (isset($item['roles']) && !$u->hasAnyRole((array)$item['roles'])) return false;
      return true;
  };

  $visibleChildren = fn($children) =>
      collect($children)->filter(fn($ch) => $canSee($ch))->values()->all();
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white border-b">
  <div class="px-4 h-14 flex items-center justify-between">
    <!-- esquerda -->
    <div class="flex items-center gap-4">
      <!-- hambúrguer -->
      <button @click="open = !open" class="md:hidden p-2 rounded hover:bg-gray-100">
        <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M6 18L18 6"/></svg>
      </button>

      <a href="{{ route('dashboard') }}" class="font-semibold">ADMS 2.0</a>

      <div class="hidden md:flex items-center gap-3">
        @foreach ($menuMain as $item)
          @if (!$canSee($item)) @continue @endif

          @if (isset($item['children']))
            @php $vis = $visibleChildren($item['children']); @endphp
            @if (count($vis) > 0)
              <div x-data="{ sub:false }" class="relative">
                <button @click="sub=!sub" class="px-2 py-1 hover:bg-gray-100 rounded">
                  {{ $item['label'] }}
                </button>
                <div x-show="sub" x-cloak @click.outside="sub=false"
                     class="absolute mt-2 w-40 bg-white border rounded shadow-md z-20">
                  @foreach ($vis as $child)
                    <a href="{{ route($child['route']) }}"
                       class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['route']) ? 'font-semibold text-blue-600' : '' }}">
                      {{ $child['label'] }}
                    </a>
                  @endforeach
                </div>
              </div>
            @endif
          @else
            <a href="{{ route($item['route']) }}"
               class="px-2 py-1 hover:bg-gray-100 rounded {{ $isActive($item['route']) ? 'font-semibold text-blue-600' : '' }}">
              {{ $item['label'] }}
            </a>
          @endif
        @endforeach
      </div>
    </div>

    <!-- direita -->
    <div class="hidden md:flex items-center gap-4">
      @foreach ($menuRight as $item)
        @if (!$canSee($item)) @continue @endif

        @if (isset($item['children']))
          @php $vis = $visibleChildren($item['children']); @endphp
          @if (count($vis) > 0)
            <div x-data="{ sub:false }" class="relative">
              <button @click="sub=!sub" class="px-2 py-1 hover:bg-gray-100 rounded">
                {{ $item['label'] }}
              </button>
              <div x-show="sub" x-cloak @click.outside="sub=false"
                   class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-md z-20">
                @foreach ($vis as $child)
                  @if ($child['route'] === 'logout')
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-100">
                        {{ $child['label'] }}
                      </button>
                    </form>
                  @else
                    <a href="{{ route($child['route']) }}"
                       class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['route']) ? 'font-semibold text-blue-600' : '' }}">
                      {{ $child['label'] }}
                    </a>
                  @endif
                @endforeach
              </div>
            </div>
          @endif
        @else
          <a href="{{ route($item['route']) }}"
             class="px-2 py-1 hover:bg-gray-100 rounded {{ $isActive($item['route']) ? 'font-semibold text-blue-600' : '' }}">
            {{ $item['label'] }}
          </a>
        @endif
      @endforeach
    </div>
  </div>

  <!-- mobile -->
  <div x-show="open" x-cloak class="md:hidden border-t bg-white p-2 space-y-1">
    @foreach ($menuMain as $item)
      @if (!$canSee($item)) @continue @endif

      @if (isset($item['children']))
        @php $vis = $visibleChildren($item['children']); @endphp
        @if (count($vis) > 0)
          <details>
            <summary class="px-3 py-2 cursor-pointer hover:bg-gray-100">{{ $item['label'] }}</summary>
            <div class="pl-4">
              @foreach ($vis as $child)
                <a href="{{ route($child['route']) }}"
                   class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['route']) ? 'font-semibold text-blue-600' : '' }}">
                  {{ $child['label'] }}
                </a>
              @endforeach
            </div>
          </details>
        @endif
      @else
        <a href="{{ route($item['route']) }}"
           class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($item['route']) ? 'font-semibold text-blue-600' : '' }}">
          {{ $item['label'] }}
        </a>
      @endif
    @endforeach

    <hr>

    @foreach ($menuRight as $item)
      @if (!$canSee($item)) @continue @endif
      @foreach ($visibleChildren($item['children'] ?? []) as $child)
        @if ($child['route'] === 'logout')
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-100">{{ $child['label'] }}</button>
          </form>
        @else
          <a href="{{ route($child['route']) }}"
             class="block px-3 py-2 hover:bg-gray-100 {{ $isActive($child['route']) ? 'font-semibold text-blue-600' : '' }}">
            {{ $child['label'] }}
          </a>
        @endif
      @endforeach
    @endforeach
  </div>
</nav>
