<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfils';

    protected $fillable = [
        'user_id',
        'telefono',
        'profesion',
        'biografia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

