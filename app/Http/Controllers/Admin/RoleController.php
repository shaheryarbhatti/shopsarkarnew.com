<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected array $protected = ['super-admin', 'customer', 'contributor'];

    public function index()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.form', [
            'role' => new Role(),
            'groups' => config('acl.groups'),
            'assigned' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($this->validPermissions($data['permissions'] ?? []));

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.form', [
            'role' => $role,
            'groups' => config('acl.groups'),
            'assigned' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->with('error', 'Super admin role cannot be modified.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        if (! in_array($role->name, $this->protected)) {
            $role->update(['name' => $data['name']]);
        }
        $role->syncPermissions($this->validPermissions($data['permissions'] ?? []));

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, $this->protected)) {
            return back()->with('error', 'This role is protected and cannot be deleted.');
        }
        $role->delete();

        return back()->with('success', 'Role deleted.');
    }

    protected function validPermissions(array $permissions): array
    {
        return array_values(array_intersect($permissions, acl_permissions()));
    }
}
