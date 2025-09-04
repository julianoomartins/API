<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// IMPORTA do Spatie
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // garante que a permissão exista
        $perm = Permission::firstOrCreate(['name' => 'profile.edit', 'guard_name' => 'web']);

        // dá a permissão à role admin
        $adminRole->givePermissionTo($perm);

        // cria o usuário admin
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $user->syncRoles([$adminRole]);
    }
}
