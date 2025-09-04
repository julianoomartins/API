{{-- resources/views/partials/menu.blade.php --}}
@php
  // Esquerda
  $menuMain = [
      [
          'label' => 'Início',
          'route' => 'dashboard',
          'icon'  => 'home',
      ],
      [
          'label' => 'Cadastros',
          'icon'  => 'folder',
          'roles' => ['admin'], // só admins
          'children' => [
              ['label' => 'Usuários', 'route' => 'users.index', 'icon' => 'user', 'perm' => 'users.view'],
          ],
      ],
  ];

  // Direita
  $menuRight = [
      [
          'label' => 'Perfil',
          'icon'  => 'user',
          'children' => [
              ['label' => 'Minha conta', 'route' => 'profile.edit', 'icon' => 'id'],
              ['label' => 'Sair',        'route' => 'logout',       'icon' => 'logout'],
          ],
      ],
  ];
@endphp
