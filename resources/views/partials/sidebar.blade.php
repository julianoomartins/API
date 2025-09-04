<aside class="sidebar transition-all duration-300 border-r bg-white">
  <nav class="pt-2">
    <ul class="space-y-1 px-3">
      <li>
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-50
                  @if(request()->routeIs('dashboard')) bg-blue-600 text-white hover:bg-blue-600 @endif">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 17.93V18h-2v1.93A8.001 8.001 0 014.07 13H6v-2H4.07A8.001 8.001 0 0111 4.07V6h2V4.07A8.001 8.001 0 0119.93 11H18v2h1.93A8.001 8.001 0 0113 19.93z"/>
          </svg>
          <span class="sidebar-text">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-50
                  @if(request()->routeIs('users.*')) bg-blue-50 text-blue-700 @endif">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.93 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
          </svg>
          <span class="sidebar-text">Gerenciar Usuários</span>
        </a>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-50
                  @if(request()->routeIs('profile.*')) bg-blue-50 text-blue-700 @endif">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm7 9a7 7 0 10-14 0h14z"/>
          </svg>
          <span class="sidebar-text">Meu Perfil</span>
        </a>
      </li>
    </ul>
  </nav>
</aside>
