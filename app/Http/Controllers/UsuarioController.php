<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        return Inertia::render('Usuarios/Index', [
            'filters' => request()->all('search'),
            'users' => User::with('role')
                ->when(
                    request('search'),
                    fn($q, $search) =>
                    $q->where('nombres', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                )
                ->orderBy('id')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'ci' => $user->ci,
                    'nombres' => $user->nombres,
                    'apellidos' => $user->apellidos,
                    'email' => $user->email,
                    'estado' => $user->estado,
                    'role' => $user->role ? $user->role->only('id', 'name') : null,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Usuarios/Create', [
            'roles' => Role::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ci' => ['required', 'max:20'],
            'nombres' => ['required', 'max:50'],
            'apellidos' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'max:50'],
            'estado' => ['required', 'boolean'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $validated['password'] = ($validated['password']);
        $validated['email_verified_at'] = now();
        $user = User::create($validated);
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role->name);
        return Redirect::route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return Inertia::render('Usuarios/Edit', [
            'user' => $usuario,
            'roles' => Role::select('id', 'name')->get(),
        ]);
    }

    public function update(User $usuario, Request $request)
    {
        $validated = $request->validate([
            'ci' => ['required', 'max:20'],
            'nombres' => ['required', 'max:50'],
            'apellidos' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $usuario->id],
            'password' => ['nullable', 'max:50'],
            'estado' => ['required', 'boolean'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = ($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);
        $role = Role::findOrFail($request->role_id);
        $usuario->syncRoles([$role->name]);

        return Redirect::route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return Redirect::route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
