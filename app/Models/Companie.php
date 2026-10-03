<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Companie extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'sitio_web',
        'ubicacion',
    ];

    public function employments(): HasMany
    {
        return $this->hasMany(Employment::class, 'companie_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'companie_id');
    }
}
