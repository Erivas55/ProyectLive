<?php

namespace Database\Seeders;

use App\Models\Companie;
use App\Models\Employment;
use App\Models\Postulation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Usuarios de prueba (contraseña de todos: "password"):
     *  admin@empleos.test        -> Administrador
     *  reclutador.a@empleos.test -> Reclutador de Empresa A
     *  reclutador.b@empleos.test -> Reclutador de Empresa B
     *  candidato@empleos.test    -> Candidato
     */
    public function run(): void
    {
        $roles = [];
        foreach ([
            Role::ADMINISTRADOR => 'Administra toda la plataforma',
            Role::RECLUTADOR => 'Gestiona las vacantes de su empresa',
            Role::CANDIDATO => 'Busca vacantes y se postula',
        ] as $nombre => $descripcion) {
            $roles[$nombre] = Role::firstOrCreate(['nombre' => $nombre], ['descripcion' => $descripcion]);
        }

        $empresaA = Companie::firstOrCreate(['nombre' => 'Empresa A'], ['ubicacion' => 'Ciudad de Guatemala']);
        $empresaB = Companie::firstOrCreate(['nombre' => 'Empresa B'], ['ubicacion' => 'Antigua Guatemala']);

        $crear = fn (string $name, string $email, string $rol, ?Companie $empresa = null) => User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => 'password',
                'email_verified_at' => now(),
                'rol_id' => $roles[$rol]->id,
                'companie_id' => $empresa?->id,
            ],
        );

        $crear('Admin', 'admin@empleos.test', Role::ADMINISTRADOR);
        $crear('Reclutador A', 'reclutador.a@empleos.test', Role::RECLUTADOR, $empresaA);
        $crear('Reclutador B', 'reclutador.b@empleos.test', Role::RECLUTADOR, $empresaB);
        $candidato = $crear('Candidato Demo', 'candidato@empleos.test', Role::CANDIDATO);

        $vacanteA = Employment::firstOrCreate(
            ['companie_id' => $empresaA->id, 'titulo' => 'Desarrollador Laravel (Empresa A)'],
            ['modalidad' => 'remoto', 'tipo_contrato' => 'tiempo_completo', 'estado' => 'activo', 'fecha_publicacion' => now()],
        );
        Employment::firstOrCreate(
            ['companie_id' => $empresaB->id, 'titulo' => 'Analista de Datos (Empresa B)'],
            ['modalidad' => 'hibrido', 'tipo_contrato' => 'temporal', 'estado' => 'activo', 'fecha_publicacion' => now()],
        );

        Postulation::firstOrCreate(
            ['employment_id' => $vacanteA->id, 'usuario_id' => $candidato->id],
            ['estado' => 'pendiente', 'fecha_postulacion' => now()],
        );
    }
}
