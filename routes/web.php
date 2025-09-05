<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;

// PÚBLICO / REDIREÇÕES
Route::get('/', fn () => redirect()->route('login'));

// ÁREA AUTENTICADA (GERAL)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::middleware(['permission:profile.edit'])->group(function () {
        Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// ADMINISTRAÇÃO
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'))->name('admin.root');
    Route::get('/', fn () => view('admin.dashboard'))->name('admin.index');


    // Console do Menu (NÃO no menu; você controla pelo banco)
    Route::get('/menu', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::post('/menu', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('admin.menu.store');

    // Usuários (rotas continuam existindo; menu vem do banco)
   Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

});

require __DIR__ . '/auth.php';
