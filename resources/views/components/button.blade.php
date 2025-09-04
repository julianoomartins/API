@props([
    'href' => '#',
    'color' => 'primary',  {{-- azul bootstrap por padrão --}}
    'icon' => null,
    'label' => 'Botão',
])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => "btn btn-$color d-inline-flex align-items-center gap-2"]) }}>
    @if($icon)
        <i class="bi bi-{{ $icon }}"></i>
    @endif
    <span>{{ $label }}</span>
</a>
