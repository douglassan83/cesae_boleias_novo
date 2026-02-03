<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME DO PROJETO — CESAE BOLEIAS
    |--------------------------------------------------------------------------
    | Esta é a página inicial da plataforma.
    | Aqui você pode carregar dados dinâmicos, estatísticas, banners etc.
    | Por enquanto, mantém apenas um exemplo simples.
    */
    public function home()
    {
        // Exemplo de variável enviada para a view
        $surname = 'santos';

        // Renderiza a view resources/views/utils/home.blade.php
        return view('utils.home', compact('surname'));
    }
}


