<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public const ADMINISTRADOR = 'Administrador';
    public const RECLUTADOR = 'Reclutador';
    public const CANDIDATO = 'Candidato';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /** Roles base: no se pueden renombrar ni eliminar porque la autorización depende de ellos. */
    public static function baseNames(): array
    {
        return [self::ADMINISTRADOR, self::RECLUTADOR, self::CANDIDATO];
    }

    public function isBase(): bool
    {
        return in_array($this->nombre, self::baseNames(), true);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}
