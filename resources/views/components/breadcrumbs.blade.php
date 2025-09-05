@props([
    // título explícito da página (substitui o último segmento)
    'title' => null,

    // trilha manual: [['label'=>'Administração','url'=>route('admin.root')], ...]
    'trail' => null,

    // mapeia labels por segmento (slug): 'users' => 'Usuários'
    'map' => [
        'admin' => 'Administração',
        'users' => 'Usuários',
        'reports' => 'Relatórios',
        'profile' => 'Perfil',
        'create' => 'Novo',
        'edit' => 'Editar',
    ],

    // segmentos a ignorar (não aparecem)
    'skip' => ['admin'],

    // mostrar ícone da home?
    'homeIcon' => true,

    'size' => '12px',
])

@php
    use Illuminate\Support\Str;

    // Se veio uma trilha manual, renderiza direto
    if (is_array($trail) && count($trail)) {
        $items = $trail;
        if ($title) {
            $items[] = ['label' => $title, 'url' => null];
        }
    } else {
        $segments = request()->segments(); // ex: ["admin","users","edit","123"]
        $items = [];
        $url = '';
        $lastIndex = count($segments) - 1;

        // Nome da rota atual ajuda a detectar "edit"/"create"
        $routeName = optional(request()->route())->getName();

        foreach ($segments as $i => $seg) {
            if ($seg === '') {
                continue;
            }

            if (in_array($seg, $skip, true)) {
                continue;
            }

            $url .= '/' . $seg;

            $label = $map[$seg] ?? Str::title(str_replace('-', ' ', $seg));

            $isLast = $i === $lastIndex;
            $isNumeric = ctype_digit($seg);
            $routeName = optional(request()->route())->getName();
            $next = $segments[$i + 1] ?? null;

            // 🚫 IGNORA ID SE ESTIVER ANTES DE "edit"
            if ($isNumeric && ($next === 'edit' || Str::endsWith($routeName, '.edit'))) {
                continue;
            }

            if ($seg === 'create' || ($routeName && Str::endsWith($routeName, '.create') && $isLast)) {
                $label = $map['create'] ?? 'Novo';
            }

            if ($seg === 'edit' || ($isNumeric && $routeName && Str::endsWith($routeName, '.edit') && $isLast)) {
                $label = $map['edit'] ?? 'Editar';
            }

            if ($isLast && $title) {
                if (!$isNumeric && $seg !== 'edit' && $seg !== 'create') {
                    $items[] = ['label' => $label, 'url' => url($url)];
                }
                $items[] = ['label' => $title, 'url' => null];
            } else {
                $items[] = ['label' => $label, 'url' => $isLast ? null : url($url)];
            }
        }

        // caso não haja segmentos (home) mas tenha título
        if (empty($items) && $title) {
            $items[] = ['label' => $title, 'url' => null];
        }
    }
@endphp

{{-- Render AdminLTE --}}
<li class="breadcrumb-item" style="font-size: {{ $size }};">
    <a href="{{ url('/') }}" aria-label="Início">
        @if ($homeIcon)
            <i class="fas fa-home"></i>
        @else
            Início
        @endif
    </a>
</li>

@foreach ($items as $item)
    @if (!empty($item['url']))
        <li class="breadcrumb-item" style="font-size: {{ $size }};">
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        </li>
    @else
        <li class="breadcrumb-item active" aria-current="page" style="font-size: {{ $size }};">
            {{ $item['label'] }}
        </li>
    @endif
@endforeach
