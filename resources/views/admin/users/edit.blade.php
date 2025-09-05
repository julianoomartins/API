@extends('layouts.app-adminlte')

@section('title', 'Editar Usuário')

@section('header_actions')
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <form method="POST" action="{{ route('users.update', $user) }}" autocomplete="off" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Ops!</strong> Corrija os campos abaixo.
                            </div>
                        @endif

                        {{-- Nome --}}
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input id="email" name="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Senha (opcional) + confirmar --}}
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="password" class="form-label">Senha (deixe em branco para não alterar)</label>
                                <div class="input-group" data-password-wrapper>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input id="password" name="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" minlength="8">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            data-action="toggle-visibility">
                                            <i class="far fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-primary" type="button"
                                            data-action="generate-password" title="Gerar senha forte">
                                            <i class="fas fa-magic"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Mínimo 8 caracteres. Se não preencher, a senha permanece
                                    a
                                    mesma.</small>
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        class="form-control" minlength="8">
                                </div>
                            </div>
                        </div>

                        {{-- Roles --}}
                        <div class="form-group mb-1">
                            <label class="form-label d-block">Funções (roles)</label>
                            <div class="row">
                                @php
                                    $oldRoles = (array) old('roles', $user->roles->pluck('name')->toArray());
                                @endphp
                                @foreach ($roles as $role)
                                    <div class="col-sm-6 col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="role_{{ $role->id }}" name="roles[]"
                                                value="{{ $role->name }}" class="custom-control-input"
                                                @checked(in_array($role->name, $oldRoles))>
                                            <label class="custom-control-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button class="btn btn-primary ml-2">
                            <i class="fas fa-save mr-1"></i> Atualizar
                        </button>
                    </div>
                </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            document.querySelectorAll('[data-password-wrapper]').forEach(function(wrap) {
                const input = wrap.querySelector('#password');
                const btnEye = wrap.querySelector('[data-action="toggle-visibility"]');
                const btnGen = wrap.querySelector('[data-action="generate-password"]');
                const confirm = document.getElementById('password_confirmation');

                if (btnEye) {
                    btnEye.addEventListener('click', function() {
                        const isPwd = input.type === 'password';
                        input.type = isPwd ? 'text' : 'password';
                        this.querySelector('i').className = isPwd ? 'far fa-eye-slash' : 'far fa-eye';
                    });
                }

                if (btnGen) {
                    btnGen.addEventListener('click', function() {
                        const s = generateStrongPassword();
                        input.value = s;
                        if (confirm) confirm.value = s;
                        input.type = 'text';
                        if (btnEye) btnEye.querySelector('i').className = 'far fa-eye-slash';
                    });
                }
            });

            function generateStrongPassword() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*';
                let out = '';
                for (let i = 0; i < 12; i++) out += chars.charAt(Math.floor(Math.random() * chars.length));
                return out;
            }
        })();
    </script>
@endpush
