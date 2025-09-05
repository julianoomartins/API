<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;

/*
|--------------------------------------------------------------------------
| PÚBLICO / REDIREÇÕES
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| ÁREA AUTENTICADA (GERAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))
        ->name('dashboard');

    // Reports (exemplo público para usuários logados)
    Route::get('/reports', fn () => view('reports.index'))
        ->name('reports.index')
        ->defaults('_menu', [
            'key'   => 'reports',
            'label' => 'Relatórios',
            'icon'  => 'fas fa-chart-line',
            'order' => 20,
        ]);

    // Perfil (somente quem tiver a permissão explicitamente)
    Route::middleware(['permission:profile.edit'])->group(function () {
        Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| ADMINISTRAÇÃO
|--------------------------------------------------------------------------
| Tudo que é administrativo vai aqui. Isso evita duplicidade de grupos.
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // Nó PAI do grupo "Administração" (apenas para aparecer no menu)
    Route::get('/', fn () => redirect()->route('dashboard'))
        ->name('admin.root')
        ->defaults('_menu', [
            'key'   => 'admin',
            'label' => 'Administração',
            'icon'  => 'fas fa-cogs',
            'order' => 90,
            'parent'=> null,
            // 'permission' => 'admin.access', // opcional, se quiser filtrar via Spatie
        ]);

    // Console do Menu (filho de Administração)
    Route::get('/menu', [MenuController::class, 'index'])
        ->name('admin.menu.index')
        ->defaults('_menu', [
            'key'        => 'admin.menu',
            'label'      => 'Console do Menu',
            'icon'       => 'fas fa-sitemap',
            'order'      => 1,
            'parent'     => 'admin',
            'permission' => 'menu.manage', // opcional
        ]);

    Route::post('/menu', [MenuController::class, 'update'])
        ->name('admin.menu.update');

    /*
    |----------------------------------------------------------------------
    | Usuários (CRUD) — também dentro de Administração
    |----------------------------------------------------------------------
    | Entrada principal "Usuários" (pai) + exemplos de filhos.
    */
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index')
        ->defaults('_menu', [
            'key'        => 'users',
            'label'      => 'Usuários',
            'icon'       => 'fas fa-users',
            'order'      => 2,
            'parent'     => 'admin',
            // 'permission' => 'users.view',
        ]);

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->defaults('_menu', [
            'key'        => 'users.create',
            'label'      => 'Novo',
            'icon'       => 'far fa-circle',
            'order'      => 2,
            'parent'     => 'users',
            'permission' => 'users.create', // opcional
        ]);

    Route::get('/users/list', [UserController::class, 'list'])
        ->name('users.list')
        ->defaults('_menu', [
            'key'    => 'users.list',
            'label'  => 'Listar',
            'icon'   => 'far fa-circle',
            'order'  => 1,
            'parent' => 'users',
            // 'permission' => 'users.view',
        ]);

    // Resource completo (mantido UMA única vez)
    Route::resource('users', UserController::class)->except(['index', 'create']);
    // ^ como já criamos 'index' e 'create' acima para entrarem no menu,
    // evitamos conflito de nomes/paths excluindo-os aqui.
});

require __DIR__ . '/auth.php';
