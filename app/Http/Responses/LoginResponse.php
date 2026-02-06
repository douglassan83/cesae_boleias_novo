<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Para onde redirecionar depois do login.
     */
    public function toResponse($request)
    {
        // Ignora qualquer "intended" e manda sempre para /rides
        return redirect('/rides');
        // Se preferires usar a config:
        // return redirect(config('fortify.home'));
    }
}
