<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilController extends Controller
{
    public function home(){
        //qualquer codigo que eu quiser, funções, variáveis etc.
        //codigo ficticio que vem de uma consulta
        $surname = 'santos';

    return view('utils.home',compact('surname'));//caminho para o view nas pastas
    }
}

