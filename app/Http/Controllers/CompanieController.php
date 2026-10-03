<?php

namespace App\Http\Controllers;

use App\Models\Companie;
use Illuminate\Http\Request;

class CompanieController extends Controller
{
    // Solo Administrador (middleware role:administrador en routes/web.php).

    public function index()
    {
        return view('empresas.index', [
            'empresas' => Companie::withCount('employments')->orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        Companie::create($this->validateData($request));

        return redirect()->route('empresas.index')->with('success', 'Empresa creada exitosamente.');
    }

    public function edit(Companie $companie)
    {
        return view('empresas.edit', ['empresa' => $companie]);
    }

    public function update(Request $request, Companie $companie)
    {
        $companie->update($this->validateData($request));

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada exitosamente.');
    }

    public function destroy(Companie $companie)
    {
        $companie->delete();

        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada exitosamente.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'sitio_web' => ['nullable', 'url', 'max:255'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
