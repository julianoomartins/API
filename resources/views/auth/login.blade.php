@extends('layouts.guest-adminlte')

@section('content')
    <p class="login-box-msg">Faça login para iniciar sua sessão</p>

    {{-- Alertas de validação --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensagem de status (ex: senha redefinida) --}}
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required
                autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Senha" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                {{--  <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Lembrar-me</label>
                </div>  --}}
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
        </div>
    </form>
@endsection
