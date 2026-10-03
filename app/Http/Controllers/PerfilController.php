<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerfilController extends Controller
{
    public function create(Request $request)
    {
        $perfil = $request->user()->perfil;

        if ($perfil) {
            return redirect()->route('perfil.show', $perfil);
        }

        return view('perfiles.create');
    }

    public function store(Request $request)
    {
        abort_if($request->user()->perfil()->exists(), 409, 'Ya tiene un perfil.');

        $data = $request->validate([
            'telefono' => ['nullable', 'string', 'max:30'],
            'profesion' => ['required', 'string', 'max:255'],
            'biografia' => ['nullable', 'string'],
        ]);

        Log::info('PERFIL: creando perfil', ['user_id' => $request->user()->id]);

        $perfil = $request->user()->perfil()->create($data);

        Log::info('PERFIL: perfil creado', ['perfil_id' => $perfil->id]);

        return redirect()->route('perfil.show', $perfil);
    }

    public function show(Request $request, Perfil $perfil)
    {
        abort_unless($perfil->user_id === $request->user()->id, 403);

        return view('perfiles.show', compact('perfil'));
    }

    public function edit(Request $request, Perfil $perfil)
    {
        abort_unless($perfil->user_id === $request->user()->id, 403);

        return view('perfiles.edit', compact('perfil'));
    }

    public function update(Request $request, Perfil $perfil)
    {
        abort_unless($perfil->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'telefono' => ['nullable', 'string', 'max:30'],
            'profesion' => ['required', 'string', 'max:255'],
            'biografia' => ['nullable', 'string'],
        ]);

        $perfil->update($data);

        return redirect()->route('perfil.show', $perfil);
    }
}