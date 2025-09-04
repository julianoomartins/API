<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function giveRole(Request $req, User $user)
    {
        $role = (string) $req->input('role');
        abort_if(!$role, 422, 'role obrigatório');

        $user->assignRole($role);
        return response()->json(['ok' => true, 'roles' => $user->getRoleNames()]);
    }

    public function revokeRole(Request $req, User $user)
    {
        $role = (string) $req->input('role');
        abort_if(!$role, 422, 'role obrigatório');

        $user->removeRole($role);
        return response()->json(['ok' => true, 'roles' => $user->getRoleNames()]);
    }

    public function givePermission(Request $req, User $user)
    {
        $permission = (string) $req->input('permission');
        abort_if(!$permission, 422, 'permission obrigatório');

        $user->givePermissionTo($permission);
        return response()->json(['ok' => true, 'perms' => $user->getPermissionNames()]);
    }

    public function revokePermission(Request $req, User $user)
    {
        $permission = (string) $req->input('permission');
        abort_if(!$permission, 422, 'permission obrigatório');

        $user->revokePermissionTo($permission);
        return response()->json(['ok' => true, 'perms' => $user->getPermissionNames()]);
    }
}
