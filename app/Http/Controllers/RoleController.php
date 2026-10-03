<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    // La autorización (solo Administrador) se aplica con el middleware role: en routes/web.php.

    public function index()
    {
        return view('roles.index', [
            'roles' => Role::orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        Role::create($this->validateData($request));

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $this->validateData($request, $role);

        if ($role->isBase()) {
            // Los roles base conservan su nombre: la autorización depende de él.
            unset($data['nombre']);
        }

        $role->update($data);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role)
    {
        if ($role->isBase()) {
            return redirect()->route('roles.index')->with('error', 'Los roles base del sistema no se pueden eliminar.');
        }

        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

    private function validateData(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:50', Rule::unique('roles', 'nombre')->ignore($role)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
