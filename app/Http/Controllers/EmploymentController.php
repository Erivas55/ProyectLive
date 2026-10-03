<?php

namespace App\Http\Controllers;

use App\Models\Companie;
use App\Models\Employment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class EmploymentController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Employment::class);

        $user = $request->user();
        $query = Employment::with('companie')->latest();

        if ($user->isReclutador()) {
            // Solo las vacantes de SU empresa (el filtro se hace en el servidor).
            $query->where('companie_id', $user->companie_id ?? 0);
        } elseif ($user->isCandidato()) {
            $query->where('estado', 'activo');
        }

        return view('vacantes.index', [
            'vacantes' => $query->get(),
            'yaPostuladas' => $user->isCandidato()
                ? $user->postulations()->pluck('employment_id')->all()
                : [],
        ]);
    }

    public function show(Request $request, Employment $employment)
    {
        Gate::authorize('view', $employment);

        $user = $request->user();

        return view('vacantes.show', [
            'vacante' => $employment->load('companie'),
            'yaPostulado' => $user->isCandidato()
                && $user->postulations()->where('employment_id', $employment->id)->exists(),
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Employment::class);

        return view('vacantes.create', ['empresas' => $this->empresasDisponibles()]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Employment::class);

        $data = $this->validateData($request);
        // El reclutador NUNCA decide la empresa: se toma del usuario autenticado.
        $data['companie_id'] = $request->user()->isAdmin()
            ? $request->validate(['companie_id' => ['required', 'exists:companies,id']])['companie_id']
            : $request->user()->companie_id;
        $data['fecha_publicacion'] = now();

        Employment::create($data);

        return redirect()->route('vacantes.index')->with('success', 'Vacante creada exitosamente.');
    }

    public function edit(Employment $employment)
    {
        Gate::authorize('update', $employment);

        return view('vacantes.edit', [
            'vacante' => $employment,
            'empresas' => $this->empresasDisponibles(),
        ]);
    }

    public function update(Request $request, Employment $employment)
    {
        Gate::authorize('update', $employment);

        $data = $this->validateData($request);

        if ($request->user()->isAdmin()) {
            $data['companie_id'] = $request->validate(['companie_id' => ['required', 'exists:companies,id']])['companie_id'];
        }
        // Reclutador: companie_id se ignora aunque lo envíen manualmente.

        $employment->update($data);

        return redirect()->route('vacantes.index')->with('success', 'Vacante actualizada exitosamente.');
    }

    public function destroy(Employment $employment)
    {
        Gate::authorize('delete', $employment);

        $employment->delete();

        return redirect()->route('vacantes.index')->with('success', 'Vacante eliminada exitosamente.');
    }

    private function empresasDisponibles()
    {
        return auth()->user()->isAdmin() ? Companie::orderBy('nombre')->get() : collect();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'modalidad' => ['required', Rule::in(['presencial', 'remoto', 'hibrido'])],
            'tipo_contrato' => ['required', Rule::in(['tiempo_completo', 'medio_tiempo', 'temporal', 'practica'])],
            'salario' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'estado' => ['required', Rule::in(['activo', 'cerrado', 'pausado'])],
        ]);
    }
}
