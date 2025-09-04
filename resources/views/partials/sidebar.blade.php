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
@endphp

<aside class="sidebar transition-all duration-300 border-r bg-white">
  <nav class="pt-2">
    <ul class="space-y-1 px-3">
      @foreach ($menuPrincipal as $item)
        @if (!$canSee($item)) @continue @endif
        <li>
          <a href="{{ route($item['rota']) }}"
             class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-50
             {{ $isActive($item['rota']) ? 'bg-blue-600 text-white hover:bg-blue-600' : '' }}">
            @if (isset($item['icone']))
              <x-icon nome="{{ $item['icone'] }}" classe="h-5 w-5 flex-shrink-0" />
            @endif
            <span class="sidebar-text">{{ $item['rotulo'] }}</span>
          </a>
        </li>
      @endforeach
    </ul>
  </nav>
</aside>
