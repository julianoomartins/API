{{-- resources/views/partials/menu.blade.php --}}
@once
    @php
        // menu da esquerda
        $menuMain = [
            [
                'label' => 'Início',
                'route' => 'dashboard',
                'icon'  => 'home',
            ],
            [
                'label' => 'Cadastros',
                'icon'  => 'folder',
                'roles' => ['admin'],
                'children' => [
                    ['label' => 'Usuários', 'route' => 'users.index', 'icon' => 'user', 'perm' => 'users.view'],
                ],
            ],
        ];

        // menu da direita
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
@endonce
