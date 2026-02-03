<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [

            // NOME
            'name' => ['required', 'string', 'max:255'],

            // EMAIL INSTITUCIONAL CESAE DIGITAL
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),

                // Validação personalizada
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@msft.cesae.pt')) {
                        $fail('O email deve terminar com @msft.cesae.pt');
                    }
                }
            ],

            // ROLE ESCOLHIDA PELO ALUNO
            'role' => [
                'required',
                Rule::in(['driver', 'passenger']), // Admin não permitido aqui
            ],

            // PASSWORD (regras do Fortify)
            'password' => $this->passwordRules(),

        ])->validate();

        // Criação do usuário
        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
            'role'     => $input['role'], // passageiro ou motorista
        ]);
    }
}
