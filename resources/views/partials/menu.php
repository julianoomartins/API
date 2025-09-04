<?php
// menu da esquerda
$menuMain = [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon'  => 'home',
    ],
    [
        'label' => 'Usuários',
        'route' => 'users.index',
        'icon'  => 'user',
        'roles' => ['admin'], // só admins
    ],
];

// menu da direita
$menuRight = [
    [
        'label' => 'Perfil',
        'icon'  => 'user',
        'children' => [
            ['label' => 'Editar Perfil', 'route' => 'profile.edit', 'icon' => 'id'],
            ['label' => 'Sair',          'route' => 'logout',       'icon' => 'logout'],
        ],
    ],
];
