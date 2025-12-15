<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Roles/Index', [
            'filters' => request()->only('search'),
            'roles' => Role::query()
                ->when(request('search'), function ($q, $search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description,
                    'estado' => $role->estado,
                ]),
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Roles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:50'],
            'description' => ['nullable', 'max:150'],
            'estado' => ['required', Rule::in(['true', 'false'])],
        ]);

        Role::create($validated);

        return Redirect::route('roles.index')->with('success', 'Rol creado Correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        return Inertia::render('Roles/Show', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'estado' => $role->estado,
            ],
        ]);
    }

    public function update(Role $role, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:50'],
            'description' => ['nullable', 'max:150'],
            'estado' => ['required', Rule::in([true, false])],
        ]);

        $role->update($validated);

        return Redirect::back()->with('success', 'Rol actualizado correctamente.');
    }


    public function destroy(Role $role)
    {
        $role->delete();

        return Redirect::route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

    public function updatePermissions(Request $request, string $id)
    {
        
        $role = Role::findOrFail($id);
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.show', $role->id)
            ->with('success', '¡Permisos actualizados correctamente!');
    }
}
