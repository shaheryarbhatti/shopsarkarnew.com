<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /** Roles that represent staff (exclude storefront roles). */
    protected function staffRoles()
    {
        return Role::whereNotIn('name', ['customer', 'contributor'])->orderBy('name')->get();
    }

    public function index()
    {
        $staffRoleNames = $this->staffRoles()->pluck('name')->all();

        $users = User::whereHas('roles', fn ($q) => $q->whereIn('name', $staffRoleNames))
            ->with('roles')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', [
            'user' => new User(['is_active' => true]),
            'roles' => $this->staffRoles(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'role' => ['required', Rule::in($this->staffRoles()->pluck('name'))],
            'is_active' => ['nullable'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active'),
        ]);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Staff user created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => $this->staffRoles(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'role' => ['required', Rule::in($this->staffRoles()->pluck('name'))],
            'is_active' => ['nullable'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
            ...($data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Staff user updated.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('super-admin')) {
            return back()->with('error', 'Super admin cannot be deleted.');
        }
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();

        return back()->with('success', 'Staff user deleted.');
    }
}
