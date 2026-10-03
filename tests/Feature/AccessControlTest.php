<?php

use App\Models\Companie;
use App\Models\Employment;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    $this->admin = User::where('email', 'admin@empleos.test')->first();
    $this->reclutadorA = User::where('email', 'reclutador.a@empleos.test')->first();
    $this->reclutadorB = User::where('email', 'reclutador.b@empleos.test')->first();
    $this->candidato = User::where('email', 'candidato@empleos.test')->first();

    $this->vacanteA = Employment::where('companie_id', $this->reclutadorA->companie_id)->first();
    $this->vacanteB = Employment::where('companie_id', $this->reclutadorB->companie_id)->first();
});

// ---------- Sidebar según rol ----------
it('administrador ve opciones administrativas en el sidebar', function () {
    $this->actingAs($this->admin)->get('/dashboard')
        ->assertOk()
        ->assertSee('Administrador')
        ->assertSee(route('roles.index'), false)
        ->assertSee(route('empresas.index'), false)
        ->assertSee(route('vacantes.index'), false)
        ->assertSee(route('postulaciones.index'), false);
});

it('reclutador no ve Roles ni Empresas en el sidebar', function () {
    $this->actingAs($this->reclutadorA)->get('/dashboard')
        ->assertOk()
        ->assertSee('Reclutador')
        ->assertDontSee(route('roles.index'), false)
        ->assertDontSee(route('empresas.index'), false)
        ->assertSee(route('vacantes.index'), false)
        ->assertSee(route('postulaciones.index'), false);
});

it('candidato ve solo vacantes y postulaciones', function () {
    $this->actingAs($this->candidato)->get('/dashboard')
        ->assertOk()
        ->assertSee('Candidato')
        ->assertDontSee(route('roles.index'), false)
        ->assertDontSee(route('empresas.index'), false)
        ->assertSee(route('vacantes.index'), false)
        ->assertSee(route('postulaciones.index'), false);
});

// ---------- Control de acceso en el servidor ----------
it('administrador accede a roles y empresas', function () {
    $this->actingAs($this->admin)->get('/roles')->assertOk();
    $this->actingAs($this->admin)->get('/empresas')->assertOk();
});

it('reclutador es rechazado al escribir /roles o /empresas', function () {
    $this->actingAs($this->reclutadorA)->get('/roles')->assertForbidden();
    $this->actingAs($this->reclutadorA)->post('/roles', ['nombre' => 'Hack'])->assertForbidden();
    $this->actingAs($this->reclutadorA)->get('/empresas')->assertForbidden();
});

it('candidato no puede administrar vacantes ni roles', function () {
    $this->actingAs($this->candidato)->get('/roles')->assertForbidden();
    $this->actingAs($this->candidato)->get('/vacantes/create')->assertForbidden();
    $this->actingAs($this->candidato)->post('/vacantes', ['titulo' => 'x'])->assertForbidden();
    $this->actingAs($this->candidato)->get("/vacantes/{$this->vacanteA->id}/edit")->assertForbidden();
    $this->actingAs($this->candidato)->delete("/vacantes/{$this->vacanteA->id}")->assertForbidden();
    $this->actingAs($this->candidato)->get('/vacantes')->assertOk();
});

it('invitado es redirigido al login', function () {
    $this->get('/vacantes')->assertRedirect('/login');
});

// ---------- Reclutador y empresa ----------
it('reclutador A consulta y edita vacantes de su empresa', function () {
    $this->actingAs($this->reclutadorA)->get('/vacantes')->assertOk()->assertSee($this->vacanteA->titulo);
    $this->actingAs($this->reclutadorA)->get("/vacantes/{$this->vacanteA->id}/edit")->assertOk();
});

it('reclutador A no ve en el listado vacantes de empresa B', function () {
    $this->actingAs($this->reclutadorA)->get('/vacantes')->assertDontSee($this->vacanteB->titulo);
});

it('reclutador A no puede operar una vacante de empresa B', function () {
    $payload = ['titulo' => 'Hack', 'modalidad' => 'remoto', 'tipo_contrato' => 'temporal', 'estado' => 'activo'];

    $this->actingAs($this->reclutadorA)->get("/vacantes/{$this->vacanteB->id}")->assertForbidden();
    $this->actingAs($this->reclutadorA)->get("/vacantes/{$this->vacanteB->id}/edit")->assertForbidden();
    $this->actingAs($this->reclutadorA)->put("/vacantes/{$this->vacanteB->id}", $payload)->assertForbidden();
    $this->actingAs($this->reclutadorA)->delete("/vacantes/{$this->vacanteB->id}")->assertForbidden();

    expect($this->vacanteB->fresh()->titulo)->not->toBe('Hack');
});

it('reclutador no puede asignar una vacante a otra empresa enviando companie_id', function () {
    $this->actingAs($this->reclutadorA)->post('/vacantes', [
        'titulo' => 'Vacante nueva',
        'modalidad' => 'remoto',
        'tipo_contrato' => 'temporal',
        'estado' => 'activo',
        'companie_id' => $this->reclutadorB->companie_id,
    ])->assertRedirect();

    expect(Employment::where('titulo', 'Vacante nueva')->first()->companie_id)
        ->toBe($this->reclutadorA->companie_id);
});

// ---------- Postulaciones ----------
it('reclutador solo ve postulaciones de su empresa', function () {
    $postulacion = $this->vacanteA->postulations()->first();

    $this->actingAs($this->reclutadorA)->get("/postulaciones/{$postulacion->id}")->assertOk();
    $this->actingAs($this->reclutadorB)->get("/postulaciones/{$postulacion->id}")->assertForbidden();
});

it('candidato no ve ni retira postulaciones ajenas', function () {
    $otro = User::factory()->create(['rol_id' => $this->candidato->rol_id]);
    $postulacion = $this->vacanteA->postulations()->first();

    $this->actingAs($otro)->get("/postulaciones/{$postulacion->id}")->assertForbidden();
    $this->actingAs($otro)->delete("/postulaciones/{$postulacion->id}")->assertForbidden();
});

it('candidato se postula solo a vacantes activas y una sola vez', function () {
    $this->actingAs($this->candidato)->post("/vacantes/{$this->vacanteB->id}/postular")->assertRedirect();
    $this->actingAs($this->candidato)->post("/vacantes/{$this->vacanteB->id}/postular")->assertRedirect();

    expect($this->vacanteB->postulations()->where('usuario_id', $this->candidato->id)->count())->toBe(1);

    $this->vacanteB->update(['estado' => 'cerrado']);
    $otro = User::factory()->create(['rol_id' => $this->candidato->rol_id]);
    $this->actingAs($otro)->post("/vacantes/{$this->vacanteB->id}/postular")->assertForbidden();
});

it('un reclutador no puede postularse', function () {
    $this->actingAs($this->reclutadorA)->post("/vacantes/{$this->vacanteA->id}/postular")->assertForbidden();
});

// ---------- Registro ----------
it('un usuario sin rol no accede a módulos de empleos', function () {
    $sinRol = User::factory()->create();

    $this->actingAs($sinRol)->get('/vacantes')->assertForbidden();
    $this->actingAs($sinRol)->get('/dashboard')->assertOk();
});
