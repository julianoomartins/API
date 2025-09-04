<nav class="sticky top-0 z-40 bg-white border-b">
  <div class="px-2 sm:px-4 h-14 flex items-center justify-between">

    <!-- ESQUERDA: hambúrguer + logo -->
    <div class="flex items-center gap-2">
      <!-- Botão: alterna entre normal <-> colapsada -->
      <button
        class="p-2 rounded hover:bg-gray-100 focus:outline-none focus:ring"
        aria-label="Alternar sidebar"
        x-data
        @click="document.body.classList.toggle('sidebar-collapse')"
      >
        <!-- ícone hambúrguer -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <!-- Texto da marca: some somente quando sidebar estiver colapsada -->
      <a href="{{ route('dashboard') }}" class="font-semibold">
        <span class="brand-text">ADMS 2.0</span>
      </a>
    </div>

    <!-- DIREITA: perfil e sair -->
    <div class="flex items-center gap-4 text-sm">
      <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1 hover:text-blue-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a7 7 0 10-14 0h14z"/>
        </svg>
        Perfil
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="inline-flex items-center gap-1 hover:text-red-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
            <path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
          </svg>
          Sair
        </button>
      </form>
    </div>
  </div>
</nav>
