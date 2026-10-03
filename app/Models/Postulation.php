<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postulation extends Model
{
    protected $fillable = [
        'employment_id',
        'usuario_id',
        'carta_presentacion',
        'estado',
        'fecha_postulacion',
    ];

    protected function casts(): array
    {
        return ['fecha_postulacion' => 'datetime'];
    }

    public function employment(): BelongsTo
    {
        return $this->belongsTo(Employment::class, 'employment_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
