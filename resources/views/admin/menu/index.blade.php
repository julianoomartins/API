@extends('layouts.app-adminlte')

@section('title', 'Console do Menu')

@push('styles')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .table-responsive thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8f9fa;
        }

        .form-control {
            min-height: 38px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title"><i class="fas fa-tools mr-1"></i> Estrutura do Menu</h3>

                    <div class="d-flex gap-2">
                        @if (session('success'))
                            <span class="badge badge-success">{{ session('success') }}</span>
                        @endif
                        <a href="{{ route('admin.menu.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Novo Menu
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.menu.update') }}" class="card-body p-0">
                @csrf

                @php
                    // Lista de opções possíveis de pai (todas as chaves existentes nos overrides)
                    $parentOptions = collect($overrides)->keys()->sort()->values();

                    // Conjuntos de checkboxes marcados
                    $checkedNewTab = collect($overrides)->filter(fn($o) => (bool) $o->new_tab)->keys()->all();
                    $checkedHidden = collect($overrides)->filter(fn($o) => (bool) $o->hidden)->keys()->all();
                @endphp

                <div class="table-responsive" style="max-height: 65vh;">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:16%">Rótulo</th>
                                <th style="width:14%">Ícone (FA)</th>
                                <th style="width:16%">Rota (nome)</th>
                                <th style="width:22%">URL (prioritária)</th>
                                <th style="width:6%">Ordem</th>
                                <th style="width:10%">Pai</th>
                                <th style="width:2%" title="Abrir em nova aba">Nova</th>
                                <th style="width:2%">Ocultar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overrides->sortBy([['order', 'asc'], ['key', 'asc']]) as $key => $ov)
                                @php $idx = $loop->index; @endphp
                                <tr>
                                    {{-- hidden para manter a referência da linha no update --}}
                                    <input type="hidden" name="key[]" value="{{ $key }}">

                                    {{-- RÓTULO --}}
                                    <td>
                                        <input type="text" name="label[]" class="form-control"
                                            value="{{ old('label.' . $idx, $ov->label) }}">
                                    </td>

                                    {{-- ÍCONE (apenas para menus raiz) --}}
                                    <td>
                                        @if (empty($ov->parent_key))
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="{{ $ov->icon ?: 'far fa-circle' }}"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="icon[]" class="form-control"
                                                    placeholder="ex: fas fa-users"
                                                    value="{{ old('icon.' . $idx, $ov->icon) }}">
                                            </div>
                                        @else
                                            {{-- Ícone vazio para manter alinhamento da tabela --}}
                                            <input type="hidden" name="icon[]"
                                                value="{{ old('icon.' . $idx, $ov->icon) }}">
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>


                                    {{-- ROTA (nome) --}}
                                    <td>
                                        <input type="text" name="route_name[]" class="form-control"
                                            placeholder="ex: users.index"
                                            value="{{ old('route_name.' . $idx, $ov->route_name) }}">
                                    </td>

                                    {{-- URL (prioritária) --}}
                                    <td>
                                        <input type="url" name="custom_url[]" class="form-control"
                                            placeholder="https://..."
                                            value="{{ old('custom_url.' . $idx, $ov->custom_url) }}">
                                    </td>

                                    {{-- ORDEM --}}
                                    <td>
                                        <input type="number" name="order[]" class="form-control text-center" min="0"
                                            step="1" value="{{ old('order.' . $idx, $ov->order) }}">
                                    </td>

                                    {{-- PAI --}}
                                    <td>
                                        @php $parentOptions = collect($overrides)->keys()->sort()->values(); @endphp
                                        <select name="parent_key[]" class="form-control">
                                            <option value="">(sem pai)</option>
                                            @foreach ($parentOptions as $opt)
                                                @if ($opt !== $key)
                                                    @php $labelOpt = $overrides[$opt]->label ?? $opt; @endphp
                                                    <option value="{{ $opt }}" @selected(old('parent_key.' . $idx, $ov->parent_key) === $opt)>
                                                        {{ $labelOpt }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- NOVA ABA --}}
                                    <td class="text-center">
                                        @php $oldNewTab = old('new_tab', collect($overrides)->filter(fn($o)=> (bool)$o->new_tab)->keys()->all()); @endphp
                                        <input type="checkbox" name="new_tab[]" value="{{ $key }}"
                                            @checked(is_array($oldNewTab) && in_array($key, $oldNewTab))>
                                    </td>

                                    {{-- OCULTAR --}}
                                    <td class="text-center">
                                        @php $oldHidden = old('hidden', collect($overrides)->filter(fn($o)=> (bool)$o->hidden)->keys()->all()); @endphp
                                        <input type="checkbox" name="hidden[]" value="{{ $key }}"
                                            @checked(is_array($oldHidden) && in_array($key, $oldHidden))>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top text-right">
                    <button class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
