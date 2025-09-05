@extends('layouts.app-adminlte')

@section('title', 'Novo Menu')

@push('styles')
<style>
  .mono { font-family: ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace; }
  .small-muted { font-size:.875rem;color:#11161a; }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-12 col-lg-10 col-xl-8">
    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Cadastrar Menu</h3>
        <a href="{{ route('admin.menu.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-arrow-left"></i> Voltar
        </a>
      </div>

      <form method="POST" action="{{ route('admin.menu.store') }}">
        @csrf

        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <strong>Ops!</strong> Verifique os campos abaixo.
              <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- ======= Básico ======= --}}
          <h5 class="mb-3">Básico</h5>

          <div class="form-group">
            <label for="label">Rótulo <span class="text-danger">*</span></label>
            <input type="text" id="label" name="label" class="form-control" value="{{ old('label') }}" required>
            <small class="small-muted">Texto que aparece no menu.</small>
          </div>

          <div class="form-group">
            <label class="d-block">Destino <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="destination" id="dest_route" value="route" {{ old('destination','route')==='route'?'checked':'' }}>
              <label class="form-check-label" for="dest_route">Rota</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="destination" id="dest_url" value="url" {{ old('destination')==='url'?'checked':'' }}>
              <label class="form-check-label" for="dest_url">URL</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="destination" id="dest_group" value="group" {{ old('destination')==='group'?'checked':'' }}>
              <label class="form-check-label" for="dest_group">Sem destino (grupo/pai)</label>
            </div>
          </div>

          <div id="field_route" class="form-group">
            <label for="route_name">Rota (nome)</label>
            <select id="route_name" name="route_name" class="form-control">
              <option value="">Selecione...</option>
              @foreach($namedRoutes as $r)
                <option value="{{ $r }}" @selected(old('route_name')===$r)>{{ $r }}</option>
              @endforeach
            </select>
            <small class="small-muted">Usa <code>route(name)</code>. Se URL também estiver preenchida, a URL é prioritária.</small>
          </div>

          <div id="field_url" class="form-group">
            <label for="custom_url">URL</label>
            <input type="url" id="custom_url" name="custom_url" class="form-control" placeholder="https://..." value="{{ old('custom_url') }}">
            <small class="small-muted">Links externos podem abrir em nova aba.</small>
          </div>

          <div class="form-row">
            <div class="form-group col-md-8">
              <label for="parent_key">Pai</label>
              <select id="parent_key" name="parent_key" class="form-control">
                <option value="">(sem pai)</option>
                @foreach ($parentOptions as $opt)
                  @php $labelOpt = $overrides[$opt]->label ?? $opt; @endphp
                  <option value="{{ $opt }}" @selected(old('parent_key')===$opt)>{{ $labelOpt }}</option>
                @endforeach
              </select>
              <small class="small-muted">Escolha o agrupador (opcional).</small>
            </div>
            <div class="form-group col-md-4">
              <label for="order">Ordem</label>
              <input type="number" id="order" name="order" class="form-control" min="0" step="1" value="{{ old('order') }}">
              <small class="small-muted">Deixe vazio para ir ao fim dos irmãos.</small>
            </div>
          </div>

          {{-- ======= Avançado ======= --}}
          <hr class="my-4">
          <button class="btn btn-sm btn-outline-secondary mb-3" type="button" data-toggle="collapse" data-target="#advancedBox">
            Mostrar Avançado
          </button>

          <div id="advancedBox" class="collapse">
            <div class="form-group">
              <label for="key">Key</label>
              <input type="text" id="key" name="key" class="form-control mono" placeholder="ex: users.reports" value="{{ old('key') }}">
              <small class="small-muted">Se vazio, será gerada a partir do rótulo (ex.: <em>Relatórios Usuários</em> → <code>relatorios.usuarios</code>).</small>
            </div>

            <div class="form-group">
              <label for="icon">Ícone (FontAwesome)</label>
              <input type="text" id="icon" name="icon" class="form-control" placeholder="ex: fas fa-users" value="{{ old('icon') }}">
            </div>

            <div class="form-row">
              <div class="form-group col-md-3">
                <div class="form-check mt-4">
                  <input class="form-check-input" type="checkbox" id="new_tab" name="new_tab" value="1" @checked(old('new_tab'))>
                  <label class="form-check-label" for="new_tab">Abrir em nova aba</label>
                </div>
              </div>
              <div class="form-group col-md-3">
                <div class="form-check mt-4">
                  <input class="form-check-input" type="checkbox" id="hidden" name="hidden" value="1" @checked(old('hidden'))>
                  <label class="form-check-label" for="hidden">Ocultar</label>
                </div>
              </div>
            </div>
          </div> {{-- /advanced --}}
        </div>

        <div class="card-footer d-flex justify-content-end">
          <button class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const destRoute = document.getElementById('dest_route');
  const destUrl   = document.getElementById('dest_url');
  const destGroup = document.getElementById('dest_group');
  const fieldRoute= document.getElementById('field_route');
  const fieldUrl  = document.getElementById('field_url');
  const urlInput  = document.getElementById('custom_url');
  const newTab    = document.getElementById('new_tab');

  const iconField = document.getElementById('field_icon');        // ← campo do ícone
  const parentKey = document.getElementById('parent_key');        // ← select do pai

  function updateVisibility(){
    const vRoute = destRoute.checked;
    const vUrl   = destUrl.checked;
    const vGroup = destGroup.checked;

    fieldRoute.style.display = vRoute ? '' : 'none';
    fieldUrl.style.display   = vUrl   ? '' : 'none';

    // Esconde ícone se for grupo OU tiver pai selecionado
    const hasParent = !!parentKey.value;
    if (vGroup || hasParent) {
      iconField.style.display = 'none';
    } else {
      iconField.style.display = '';
    }

    if (vGroup) {
      document.getElementById('route_name').value = '';
      urlInput.value = '';
    }
  }

  // auto "nova aba" se URL externa
  function autoNewTab(){
    const v = (urlInput.value || '').trim();
    if (!v) return;
    const isExternal = /^https?:\/\//i.test(v);
    if (isExternal) newTab.checked = true;
  }

  destRoute.addEventListener('change', updateVisibility);
  destUrl.addEventListener('change', updateVisibility);
  destGroup.addEventListener('change', updateVisibility);
  parentKey.addEventListener('change', updateVisibility);
  urlInput.addEventListener('blur', autoNewTab);

  updateVisibility();
})();
</script>
@endpush

