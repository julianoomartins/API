<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // limpa cache de permissões
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // defina suas permissões
        $perms = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'reports.view', 'menus.toggle',
        ];
        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // crie roles
        $admin   = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'gerente-comercial', 'guard_name' => 'web']);

        // dê permissões às roles
        $admin->givePermissionTo($perms);
        $manager->givePermissionTo(['reports.view']);

        // exemplo: dar role para um usuário existente
        if ($user = User::first()) {
            $user->syncRoles(['admin']);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
