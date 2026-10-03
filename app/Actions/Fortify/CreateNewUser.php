<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Log;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Log::info('REGISTRO: inicio del proceso', [
            'email' => $input['email'] ?? null,
        ]);

        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        Log::info('REGISTRO: validacion completada');

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        // Todo registro público nace como Candidato (rol_id no es asignable por el formulario).
        $user->forceFill([
            'rol_id' => Role::where('nombre', Role::CANDIDATO)->value('id'),
        ])->save();

        Log::info('REGISTRO: usuario creado', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return $user;
    }
}
