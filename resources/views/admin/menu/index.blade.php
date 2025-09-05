@extends('layouts.app-adminlte')
@section('title','Console do Menu')
@section('header','Console do Menu')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Console do Menu</li>
@endsection

@section('content')
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-sitemap mr-1"></i> Estrutura do Menu</h3>
    <div class="card-tools">
      <button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
    </div>
  </div>

  <form action="{{ route('admin.menu.update') }}" method="POST">
    @csrf
    <div class="card-body p-0">
      <table class="table table-striped table-hover mb-0">
        <thead class="thead-light">
          <tr>
            <th style="width:220px">Key</th>
            <th>Rótulo</th>
            <th style="width:160px">Ícone (FA)</th>
            <th style="width:140px">Rota (nome)</th>
            <th style="width:240px">URL (prioritária)</th>
            <th style="width:80px">Ordem</th>
            <th style="width:160px">Pai</th>
            <th style="width:80px">Nova aba</th>
            <th style="width:80px">Ocultar</th>
          </tr>
        </thead>
        <tbody>
          @php
            $render = function($nodes, $level = 0) use (&$render, $overrides, $tree) {
              foreach ($nodes as $n) {
                $ov = $overrides[$n['key']] ?? null;

                // valor atual
                $currentParent = $ov->parent_key ?? ($n['parent'] ?? null);

                // lista de opções para o select pai
                $allKeys = collect($overrides->keys())
                              ->merge(collect($tree)->pluck('key'))
                              ->unique()->values()->all();

                echo '<tr>';
                // KEY
                echo   '<td>';
                echo     '<input type="hidden" name="key[]" value="'.e($n['key']).'">';
                echo     str_repeat('&nbsp;&nbsp;&nbsp;',$level).'<code>'.e($n['key']).'</code>';
                echo   '</td>';

                // LABEL
                echo   '<td><input class="form-control form-control-sm" name="label[]" value="'.e($ov->label ?? $n['label']).'"></td>';

                // ICON
                echo   '<td><input class="form-control form-control-sm" name="icon[]" value="'.e($ov->icon ?? $n['icon']).'"></td>';

                // ROUTE NAME
                echo   '<td><input class="form-control form-control-sm" name="route_name[]" placeholder="ex: users.index" value="'.e($ov->route_name ?? $n['route'] ?? '').'"></td>';

                // CUSTOM URL
                echo   '<td><input class="form-control form-control-sm" name="custom_url[]" placeholder="https://..." value="'.e($ov->custom_url ?? '').'"></td>';

                // ORDER
                echo   '<td><input type="number" class="form-control form-control-sm" name="order[]" value="'.e($ov->order ?? $n['order']).'"></td>';

                // PARENT
                echo   '<td><select name="parent_key[]" class="form-control form-control-sm">';
                echo     '<option value="">(sem pai)</option>';
                foreach ($allKeys as $optKey) {
                  if ($optKey === $n['key']) continue;
                  $selected = $currentParent === $optKey ? 'selected' : '';
                  echo '<option value="'.e($optKey).'" '.$selected.'>'.e($optKey).'</option>';
                }
                echo   '</select></td>';

                // NEW TAB
                $newTabChecked = ($ov?->new_tab ?? false) ? 'checked' : '';
                echo   '<td class="text-center"><input type="checkbox" name="new_tab[]" value="'.e($n['key']).'" '.$newTabChecked.'></td>';

                // HIDDEN
                $hiddenChecked = ($ov?->hidden ?? false) ? 'checked' : '';
                echo   '<td class="text-center"><input type="checkbox" name="hidden[]" value="'.e($n['key']).'" '.$hiddenChecked.'></td>';

                echo '</tr>';

                if (!empty($n['children'])) $render($n['children'], $level+1);
              }
            };
          @endphp
          {!! $render($tree) !!}
        </tbody>
      </table>
    </div>
    <div class="card-footer text-right">
      <button class="btn btn-primary">
        <i class="fas fa-save mr-1"></i> Salvar alterações
      </button>
    </div>
  </form>
</div>
@endsection
