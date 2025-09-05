@props(['title' => null])

@php
    $segments = request()->segments();
    $breadcrumbs = [];
    $url = '';

    foreach ($segments as $key => $segment) {
        $url .= '/' . $segment;
        $breadcrumbs[] = [
            'label' => ucfirst(str_replace('-', ' ', $segment)),
            'url'   => $key < count($segments) - 1 ? url($url) : null,
        ];
    }
@endphp

<li class="breadcrumb-item">
    <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
</li>
@foreach ($breadcrumbs as $item)
    @if ($item['url'])
        <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
    @else
        <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
    @endif
@endforeach

@if($title)
    <li class="breadcrumb-item active">{{ $title }}</li>
@endif
