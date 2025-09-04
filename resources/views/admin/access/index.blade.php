{{-- Se você usa um layout, troque para @extends('layouts.app') --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Controle de Acesso — Roles & Permissões</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Se já tem AdminLTE/Bootstrap locais, aponte para seus caminhos --}}
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

    <style>
        .badge-role { margin: 0 4px 4px 0; }
        .chip {
            display:inline-flex; align-items:center; gap:.4rem;
            padding:.25rem .5rem; border-radius:999px; background:#f1f5f9; border:1px solid #e2e8f0; font-size:.85rem;
        }
        .chip .rm { cursor:pointer; }
        .modal-body { max-height: 60vh; overflow:auto; }
        .sticky-tools { position: sticky; top: 0; padding: .5rem 0; background: white; z-index: 2; }
    </style>
</head>
<body class="hold-transition layout-top-nav">

<div class="content-wrapper p-3">
    <section class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Controle de Acesso</h1>
                <a href="{{ route('admin.access') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-rotate"></i> Recarregar
                </a>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:28%">Usuário</th>
                                    <th style="width:30%">Roles</th>
                                    <th style="width:22%">Adicionar Role</th>
                                    <th style="width:20%" class="text-right">Permissões</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr data-user-id="{{ $user->id }}">
                                        <td>
                                            <div class="font-weight-bold">{{ $user->name ?? '—' }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </td>

                                        {{-- ROLES (chips removíveis) --}}
                                        <td>
                                            <div class="d-flex flex-wrap">
                                                @forelse($user->roles as $role)
                                                    <span class="chip mb-1" data-role="{{ $role->name }}">
                                                        <i class="fas fa-user-shield"></i> {{ $role->name }}
                                                        <i class="fas fa-times rm text-danger"
                                                           title="Remover role"
                                                           onclick="revokeRole({{ $user->id }}, '{{ $role->name }}', this)"></i>
                                                    </span>
                                                @empty
                                                    <span class="text-muted">Sem roles</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        {{-- SELECT PARA ADICIONAR ROLE --}}
                                        <td>
                                            <div class="input-group">
                                                <select class="form-control" id="role-select-{{ $user->id }}">
                                                    <option value="">Selecione um role…</option>
                                                    @foreach($allRoles as $r)
                                                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary"
                                                            onclick="assignRole({{ $user->id }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- PERMISSÕES (botão abre modal) --}}
                                        <td class="text-right">
                                            <button class="btn btn-sm btn-outline-primary"
                                                    onclick='openPermsModal({{ $user->id }}, @json($user->permissions->pluck("name")), @json($allPermissions->pluck("name")))'>
                                                <i class="fas fa-key"></i> Gerenciar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- Modal de permissões --}}
<div class="modal fade" id="permsModal" tabindex="-1" aria-labelledby="permsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="permsModalLabel" class="modal-title"><i class="fas fa-key"></i> Permissões do usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="sticky-tools d-flex justify-content-between align-items-center">
              <input type="text" class="form-control mr-2" id="permFilter" placeholder="Filtrar permissões…"
                     oninput="filterPerms()">
              <button class="btn btn-sm btn-outline-secondary" onclick="clearFilter()">Limpar filtro</button>
          </div>
          <div id="permList" class="mt-2"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

{{-- JS (Bootstrap/ALTE) --}}
<script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function toast(msg, type='success') {
    // Simples feedback visual — ajuste para AdminLTE Toast se quiser
    const c = type === 'success' ? 'alert-success' : 'alert-danger';
    const el = document.createElement('div');
    el.className = 'alert ' + c;
    el.style.position = 'fixed';
    el.style.right = '1rem';
    el.style.bottom = '1rem';
    el.style.zIndex = 2000;
    el.textContent = msg;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
}

async function assignRole(userId) {
    const sel = document.getElementById('role-select-' + userId);
    const role = sel.value;
    if (!role) return;

    const res = await fetch(`/admin/users/${userId}/role`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type':'application/json'},
        body: JSON.stringify({ role })
    });

    if (res.ok) {
        // Injeta chip sem recarregar
        const row = document.querySelector(`tr[data-user-id="${userId}"] td:nth-child(2) > div`);
        const chip = document.createElement('span');
        chip.className = 'chip mb-1';
        chip.setAttribute('data-role', role);
        chip.innerHTML = `<i class="fas fa-user-shield"></i> ${role}
                          <i class="fas fa-times rm text-danger" title="Remover role"
                             onclick="revokeRole(${userId}, '${role}', this)"></i>`;
        row.appendChild(chip);
        sel.value = '';
        toast('Role atribuído');
    } else {
        const t = await res.text();
        toast('Falha ao atribuir role: ' + t, 'error');
    }
}

async function revokeRole(userId, role, el) {
    const res = await fetch(`/admin/users/${userId}/role`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type':'application/json'},
        body: JSON.stringify({ role })
    });

    if (res.ok) {
        // Remove chip
        const chip = el.closest('.chip');
        if (chip) chip.remove();
        toast('Role removido');
    } else {
        const t = await res.text();
        toast('Falha ao remover role: ' + t, 'error');
    }
}

let currentUserId = null;
let allPerms = [];
let userPerms = new Set();

function openPermsModal(userId, userPermissions, allPermissions) {
    currentUserId = userId;
    allPerms = allPermissions || [];
    userPerms = new Set(userPermissions || []);
    renderPerms();
    $('#permsModal').modal('show');
}

function renderPerms() {
    const container = document.getElementById('permList');
    container.innerHTML = '';
    const filter = (document.getElementById('permFilter').value || '').toLowerCase();

    allPerms
        .filter(p => p.toLowerCase().includes(filter))
        .forEach(p => {
            const id = `perm_${p.replace(/[^a-z0-9_\.:-]/gi, '_')}`;
            const wrap = document.createElement('div');
            wrap.className = 'form-check';

            const checked = userPerms.has(p) ? 'checked' : '';
            wrap.innerHTML = `
                <input class="form-check-input" type="checkbox" id="${id}" ${checked}
                       onchange="togglePermission('${p}', this.checked)">
                <label class="form-check-label" for="${id}">${p}</label>
            `;
            container.appendChild(wrap);
        });

    if (!container.children.length) {
        container.innerHTML = '<div class="text-muted">Nenhuma permissão encontrada</div>';
    }
}

function filterPerms() { renderPerms(); }
function clearFilter() {
    document.getElementById('permFilter').value = '';
    renderPerms();
}

async function togglePermission(permission, on) {
    const url = `/admin/users/${currentUserId}/permission`;
    const res = await fetch(url, {
        method: on ? 'POST' : 'DELETE',
        headers: {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type':'application/json'},
        body: JSON.stringify({ permission })
    });
    if (res.ok) {
        if (on) userPerms.add(permission); else userPerms.delete(permission);
        toast(on ? 'Permissão atribuída' : 'Permissão removida');
    } else {
        const t = await res.text();
        toast('Falha ao atualizar permissão: ' + t, 'error');
        // rollback visual
        renderPerms();
    }
}
</script>
</body>
</html>
