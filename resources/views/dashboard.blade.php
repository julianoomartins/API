@extends('layouts.app')

@section('title', '')
@section('icon', '')
@section('action')

@endsection

@section('header')
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Dashboard
  </h2>
@endsection

@section('content')
  <div class="bg-white rounded-xl shadow p-6">
    <p class="text-gray-700">Você está logado!</p>

    {{-- Espaço reservado para cards, gráficos, métricas, etc --}}
    {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
      <x-dashboard-card titulo="Usuários" valor="153" icone="user" />
      <x-dashboard-card titulo="Projetos" valor="12" icone="folder" />
    </div> --}}
  </div>
@endsection
