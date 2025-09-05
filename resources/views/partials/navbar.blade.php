@php
  $items = collect($menuTree ?? [])->values();
  $isActive = fn(array $item) => \App\Support\MenuBuilder::isActive($item);
@endphp

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  {{-- Esquerda: botão da sidebar + itens do menu dinâmico --}}
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    @foreach ($items as $item)
      @php $hasChildren = !empty($item['children']); @endphp

      @if ($hasChildren)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ $isActive($item) ? 'active' : '' }}"
             href="#" id="drop-{{ $item['key'] }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="{{ $item['icon'] }} mr-1"></i> {{ $item['label'] }}
          </a>
          <div class="dropdown-menu" aria-labelledby="drop-{{ $item['key'] }}">
            @foreach ($item['children'] as $child)
              @php $grand = !empty($child['children']); @endphp

              @if ($grand)
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">
                  <i class="{{ $child['icon'] }} mr-2"></i>{{ $child['label'] }}
                </h6>
                @foreach ($child['children'] as $gc)
                  <a href="{{ $gc['url'] ?? '#' }}" class="dropdown-item {{ $isActive($gc) ? 'active' : '' }}">
                    <i class="{{ $gc['icon'] }} mr-2"></i>{{ $gc['label'] }}
                  </a>
                @endforeach
              @else
                <a href="{{ $child['url'] ?? '#' }}" class="dropdown-item {{ $isActive($child) ? 'active' : '' }}">
                  <i class="{{ $child['icon'] }} mr-2"></i>{{ $child['label'] }}
                </a>
              @endif
            @endforeach
          </div>
        </li>
      @else
        <li class="nav-item">
          <a href="{{ $item['url'] ?? '#' }}" class="nav-link {{ $isActive($item) ? 'active' : '' }}">
            <i class="{{ $item['icon'] }} mr-1"></i> {{ $item['label'] }}
          </a>
        </li>
      @endif
    @endforeach
  </ul>

  {{-- Direita: ações e usuário --}}
  <ul class="navbar-nav ml-auto">
    @hasSection('action')
      <li class="nav-item d-none d-md-block">@yield('action')</li>
    @endif
    @auth
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#"><i class="far fa-user-circle"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
          @if (Route::has('profile.edit'))
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
              <i class="fas fa-id-card mr-2"></i> Perfil
            </a>
          @endif
          <div class="dropdown-divider"></div>
          <form action="{{ route('logout') }}" method="POST" class="px-3">@csrf
            <button class="btn btn-sm btn-outline-secondary btn-block">
              <i class="fas fa-sign-out-alt mr-1"></i> Sair
            </button>
          </form>
        </div>
      </li>
    @endauth
  </ul>
</nav>
