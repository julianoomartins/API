<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccessPageController extends Controller
{
    public function index()
    {
        // Ajuste o with() conforme seus campos
        $users = User::query()
            ->with(['roles', 'permissions'])
            ->orderBy('name')
            ->paginate(10);

        $allRoles = Role::orderBy('name')->get(['id','name']);
        $allPermissions = Permission::orderBy('name')->get(['id','name']);

        return view('admin.access.index', compact('users','allRoles','allPermissions'));
    }
}
