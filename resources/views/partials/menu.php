<?php

// Menu lateral esquerdo (principal)
$menuPrincipal = [
    [
        'rotulo' => 'Dashboard',
        'rota'   => 'dashboard',
        'icone'  => 'home',
    ],
    [
        'rotulo' => 'Usuários',
        'rota'   => 'users.index',
        'icone'  => 'user',
        'requer_roles' => ['admin'], // visível apenas para administradores
    ],
];

// Menu do usuário (lado direito/topo)
$menuUsuario = [
    [
        'rotulo' => 'Perfil',
        'icone'  => 'user',
        'submenu' => [
            [
                'rotulo' => 'Editar Perfil',
                'rota'   => 'profile.edit',
                'icone'  => 'id',
            ],
            [
                'rotulo' => 'Sair',
                'rota'   => 'logout',
                'icone'  => 'logout',
                'tipo'   => 'form-post', // indica que deve ser usado em um form com POST
            ],
        ],
    ],
];
