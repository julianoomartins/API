@extends('layouts.app-adminlte')

@section('title', 'Usuários')
@section('icon', 'bi bi-people-fill')

{{-- Botão principal ao lado do breadcrumb --}}
@section('header_actions')
    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-person-plus-fill mr-1"></i> Novo Usuário
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
            <form method="GET" action="{{ route('users.index') }}" class="form-inline mb-2">
                {{-- Seleção de itens por página --}}
                <select name="per_page" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    @foreach ([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" @selected(request('per_page', $users->perPage()) == $n)>
                            {{ $n }}/página
                        </option>
                    @endforeach
                </select>

                {{-- Botão limpar filtros --}}
                @if (request()->hasAny(['q', 'per_page', 'sort', 'direction']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i> Limpar
                    </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="thead-light">
                    <tr>
                        <th style="width:26%">
                            <a href="{{ route(
                                'users.index',
                                array_merge(request()->all(), [
                                    'sort' => 'name',
                                    'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc',
                                ]),
                            ) }}"
                                class="text-dark text-decoration-none">
                                Nome
                                @if (request('sort') === 'name')
                                    <i
                                        class="bi bi-caret-{{ request('direction') === 'asc' ? 'up-fill' : 'down-fill' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width:30%">
                            <a href="{{ route(
                                'users.index',
                                array_merge(request()->all(), [
                                    'sort' => 'email',
                                    'direction' => request('sort') === 'email' && request('direction') === 'asc' ? 'desc' : 'asc',
                                ]),
                            ) }}"
                                class="text-dark text-decoration-none">
                                Email
                                @if (request('sort') === 'email')
                                    <i
                                        class="bi bi-caret-{{ request('direction') === 'asc' ? 'up-fill' : 'down-fill' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width:28%">Funções</th>
                        <th class="text-right" style="width:16%">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle text-secondary mr-2"></i>
                                    <span class="font-weight-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">
                                @php
                                    $funcoes = $user->roles->pluck('name')->values();
                                @endphp

                                @if ($funcoes->isEmpty())
                                    <span class="badge badge-light text-muted">—</span>
                                @else
                                    @foreach ($funcoes->take(2) as $funcao)
                                        <span class="badge badge-secondary mr-1 mb-1">{{ $funcao }}</span>
                                    @endforeach
                                    @if ($funcoes->count() > 2)
                                        <span class="badge badge-pill badge-info" data-toggle="tooltip"
                                            title="{{ $funcoes->implode(', ') }}">
                                            +{{ $funcoes->count() - 2 }}
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-right align-middle">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary btn-sm mr-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Deseja realmente excluir este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Nenhum usuário encontrado.
                                <a href="{{ route('users.create') }}">Criar usuário</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }}
                </small>
                {{-- Paginação com preservação dos filtros e ordenação --}}
                {{ $users->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
