<?php

namespace App\Http\Controllers;

use App\Models\Employment;
use App\Models\Postulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostulationController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Postulation::class);

        $user = $request->user();
        $query = Postulation::with(['employment.companie', 'usuario'])->latest();

        if ($user->isReclutador()) {
            $query->whereHas('employment', fn ($q) => $q->where('companie_id', $user->companie_id ?? 0));
        } elseif ($user->isCandidato()) {
            $query->where('usuario_id', $user->id);
        }

        return view('postulaciones.index', ['postulaciones' => $query->get()]);
    }

    public function show(Postulation $postulation)
    {
        Gate::authorize('view', $postulation);

        return view('postulaciones.show', [
            'postulacion' => $postulation->load(['employment.companie', 'usuario']),
        ]);
    }

    public function store(Request $request, Employment $employment)
    {
        Gate::authorize('create', Postulation::class);
        Gate::authorize('view', $employment); // el candidato solo puede postularse a vacantes activas

        $data = $request->validate(['carta_presentacion' => ['nullable', 'string', 'max:5000']]);

        // usuario_id sale de la sesión, nunca del formulario.
        Postulation::firstOrCreate(
            ['employment_id' => $employment->id, 'usuario_id' => $request->user()->id],
            [
                'carta_presentacion' => $data['carta_presentacion'] ?? null,
                'estado' => 'pendiente',
                'fecha_postulacion' => now(),
            ],
        );

        return redirect()->route('postulaciones.index')->with('success', 'Te postulaste correctamente.');
    }

    public function destroy(Postulation $postulation)
    {
        Gate::authorize('delete', $postulation);

        $postulation->delete();

        return redirect()->route('postulaciones.index')->with('success', 'Postulación retirada.');
    }
}
