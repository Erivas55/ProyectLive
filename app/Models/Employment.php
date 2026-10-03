<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employment extends Model
{
    protected $fillable = [
        'companie_id',
        'titulo',
        'descripcion',
        'ubicacion',
        'modalidad',
        'tipo_contrato',
        'salario',
        'estado',
        'fecha_publicacion',
    ];

    protected function casts(): array
    {
        return [
            'salario' => 'decimal:2',
            'fecha_publicacion' => 'datetime',
        ];
    }

    public function companie(): BelongsTo
    {
        return $this->belongsTo(Companie::class, 'companie_id');
    }

    public function postulations(): HasMany
    {
        return $this->hasMany(Postulation::class, 'employment_id');
    }
}
