<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return inertia('Roles/Index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        // dd($permissions);
        return inertia('Roles/Create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index');
    }

    public function edit($id)
    {
        $permissions = Permission::all();
        $role = Role::find($id);
        $role->permissionss = $role->permissions->map(function ($permission) {
            return $permission->name; // Return only the 'name' of the permission
        });
        // dd($role->permissionss);
        return inertia('Roles/Edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // dd($request->all());
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissionss);
        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index');
    }
}
