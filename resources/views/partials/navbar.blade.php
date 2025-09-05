@php
  // Fonte do menu
  $items = collect($menuTree ?? [])->values();

  // Ativo (usa seu MenuBuilder)
  $isActive = fn(array $it) => \App\Support\MenuBuilder::isActive($it);

  // Resolve URL do item (prioriza custom_url; depois route_name)
  $urlOf = function (array $n) {
    if (!empty($n['url'])) return $n['url'];
    if (!empty($n['custom_url'])) return $n['custom_url'];
    if (!empty($n['route_name']) && \Illuminate\Support\Facades\Route::has($n['route_name'])) {
      try { return route($n['route_name']); } catch (\Throwable $e) {}
    }
    return '#';
  };

  /**
   * Renderer recursivo para submenus em cascata.
   * IMPORTANTE: ícones REMOVIDOS dos níveis internos.
   */
  $renderSub = function(array $children, int $level = 1) use (&$renderSub, $isActive, $urlOf) {
    echo '<div class="dropdown-menu">';
    foreach ($children as $child) {
      $temFilhos = !empty($child['children']);

      $temNetos = $temFilhos && collect($child['children'])->contains(
        fn($filho) => is_array($filho['children'] ?? null) && count($filho['children']) > 0
      );

      $classeSeta = $temFilhos ? ($temNetos ? 'submenu-duplo' : 'submenu-simples') : '';
      $rotulo     = $child['label'] ?? $child['key'];

      if ($temFilhos) {
        echo '<div class="dropdown-submenu '.$classeSeta.'">';
        echo '  <a href="#" class="dropdown-item d-flex justify-content-between align-items-center'.($isActive($child) ? ' active' : '').'">';
        echo '    <span>'.e($rotulo).'</span>';
        echo '    <i class="fas '.($temNetos ? 'fa-angle-double-right' : 'fa-angle-right').' ml-2 text-muted"></i>';
        echo '  </a>';
        $renderSub($child['children'], $level + 1);
        echo '</div>';
      } else {
        echo '<a href="'.e($urlOf($child)).'" class="dropdown-item'.($isActive($child) ? ' active' : '').'">';
        echo e($rotulo).'</a>';
      }
    }
    echo '</div>';
  };
@endphp

@push('styles')
<style>
/* Navbar compacto */
.main-header.navbar { padding: .25rem .75rem; }
.navbar-brand { padding: .25rem 0; margin-right: .5rem; }
.navbar-nav .nav-link { padding: .35rem .6rem; }

/* Submenu em cascata (multi-nível) */
.dropdown-submenu { position: relative; }
.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -0.25rem;
  margin-left: .1rem;
  display: none;
}

/* Itens */
.dropdown-menu .dropdown-item { padding: .45rem .9rem; }
.dropdown-menu .dropdown-header { font-size: .85rem; }

/* Ajustes de foco/hover */
.dropdown-item:focus, .dropdown-item:hover {
  text-decoration: none;
}

/* Evita que um submenu aberto "grude" após click fora */
.show > .dropdown-menu { display: block; }
</style>
@endpush

<nav class="main-header navbar navbar-expand-md navbar-white navbar-light">
  <div class="container-fluid">

    {{-- LOGO / BRAND --}}
    <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center">
      <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
           alt="Logo" class="brand-image img-circle elevation-1" style="max-height:26px;opacity:.9;">
    </a>

    {{-- Toggler mobile --}}
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topnav"
            aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="topnav">
      {{-- ESQUERDA: itens de navegação dinâmicos --}}
      <ul class="navbar-nav">
        @foreach ($items as $item)
          @php $hasChildren = !empty($item['children']); @endphp

          @if ($hasChildren)
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle {{ $isActive($item) ? 'active' : '' }}"
                 href="#" id="drop-{{ $item['key'] }}" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">
                @if (!empty($item['icon'])) <i class="{{ $item['icon'] }} mr-1"></i> @endif
                {{ $item['label'] ?? $item['key'] }}
              </a>
              @php $renderSub($item['children'], 1); @endphp
            </li>
          @else
            <li class="nav-item">
              <a href="{{ $urlOf($item) }}" class="nav-link {{ $isActive($item) ? 'active' : '' }}">
                @if (!empty($item['icon'])) <i class="{{ $item['icon'] }} mr-1"></i> @endif
                {{ $item['label'] ?? $item['key'] }}
              </a>
            </li>
          @endif
        @endforeach
      </ul>

      {{-- DIREITA: ações / usuário --}}
      <ul class="navbar-nav ml-auto">
        @hasSection('action')
          <li class="nav-item d-none d-md-block">@yield('action')</li>
        @endif

        @auth
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
              <i class="far fa-user-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              @if (Route::has('profile.edit'))
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                  <i class="fas fa-id-card mr-2"></i> Perfil
                </a>
              @endif

              @can('menu.manage')
                <a href="{{ route('admin.menu.index') }}" class="dropdown-item">
                  <i class="fas fa-sitemap mr-2"></i> Gerenciar menu
                </a>
              @else
                @role('admin')
                  <a href="{{ route('admin.menu.index') }}" class="dropdown-item">
                    <i class="fas fa-sitemap mr-2"></i> Gerenciar menu
                  </a>
                @endrole
              @endcan

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
    </div>
  </div>
</nav>

@push('scripts')
<script>
(function () {
  function ativarHoverDesktop() {
    const larguraMinimaDesktop = 768;

    if (window.innerWidth >= larguraMinimaDesktop) {
      // Habilita hover apenas no desktop
      $('.dropdown-submenu').on('mouseenter', function () {
        $(this).children('.dropdown-menu').first().stop(true, true).fadeIn(150);
      }).on('mouseleave', function () {
        $(this).children('.dropdown-menu').first().stop(true, true).fadeOut(150);
      });
    } else {
      // Remove qualquer comportamento de hover no mobile
      $('.dropdown-submenu').off('mouseenter mouseleave');
    }
  }

  $(document).ready(function () {
    ativarHoverDesktop();

    // Fecha submenus quando clicar fora (comportamento consistente)
    $(document).on('click', function (e) {
      if ($(e.target).closest('.dropdown-menu, .dropdown-toggle, .dropdown-submenu').length === 0) {
        $('.dropdown-menu').hide();
      }
    });

    $(window).on('resize', ativarHoverDesktop);
  });
})();
</script>
@endpush
