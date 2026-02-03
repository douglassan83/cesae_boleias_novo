<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| ROTAS DE TESTE / DESENVOLVIMENTO
|--------------------------------------------------------------------------
| Remova se não estiver usando mais.
*/

Route::get('/react-test', function () {
    return Inertia::render('Welcome');
}); // rota apenas para testar React

// ❗ ROTAS DE TESTE — REMOVA SE NÃO USA
// Route::get('/hello', fn() => view('utils.helloView'))->name('utils.hello');
// Route::get('/turma/{nomeTurma}', fn($nomeTurma) => view('utils.turmaView', compact('nomeTurma')))
//     ->name('turma.name');



/*
|--------------------------------------------------------------------------
| HOME / PÁGINAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', [UtilController::class, 'home'])->name('utils.welcome');



/*
|--------------------------------------------------------------------------
| ROTAS DE USUÁRIOS (PROTEGIDAS)
|--------------------------------------------------------------------------
| Todas as rotas de gestão de usuários exigem login.
| Seguem padrão RESTful e nomes consistentes.
*/

Route::middleware('auth')->group(function () {

    // Listar todos os usuários
    Route::get('/users', [UserController::class, 'allUsers'])
        ->name('users.all');

    // Ver detalhes de um usuário
    Route::get('/users/{id}', [UserController::class, 'viewUser'])
        ->name('users.view');

    // Formulário para adicionar novo usuário
    Route::get('/users/add', [UserController::class, 'addUser'])
        ->name('users.add');

    // Criar usuário
    Route::post('/users', [UserController::class, 'storeUser'])
        ->name('users.store');

    // Atualizar usuário
    Route::put('/users/{id}', [UserController::class, 'updateUser'])
        ->name('users.update');

    // Apagar usuário
    Route::delete('/users/{id}', [UserController::class, 'deleteUser'])
        ->name('users.delete');
});



/*
|--------------------------------------------------------------------------
| ROTAS DE BOLEIAS (RIDES)
|--------------------------------------------------------------------------
| Todas protegidas por auth.
| Organizadas e sem conflitos.
*/

Route::middleware('auth')->group(function () {

    // Listar todas as boleias
    Route::get('/rides', [RideController::class, 'allRides'])
        ->name('rides.all');

    // Formulário para criar boleia
    Route::get('/rides/add', [RideController::class, 'addRide'])
        ->name('rides.add');

    // Criar boleia
    Route::post('/rides', [RideController::class, 'storeRide'])
        ->name('rides.store');

    // Ver detalhes de uma boleia
    Route::get('/rides/{ride}', [RideController::class, 'viewRide'])
        ->name('rides.view');

    // Editar boleia
    Route::get('/rides/{ride}/edit', [RideController::class, 'editRide'])
        ->name('rides.edit');

    // Atualizar boleia
    Route::put('/rides/{ride}', [RideController::class, 'updateRide'])
        ->name('rides.update');

    // Apagar boleia
    Route::delete('/rides/{ride}', [RideController::class, 'deleteRide'])
        ->name('rides.delete');
});



/*
|--------------------------------------------------------------------------
| ROTAS DE PEDIDOS DE BOLEIA (RIDE REQUESTS)
|--------------------------------------------------------------------------
| Mantidas separadas para clareza.
*/

Route::middleware('auth')->group(function () {

    // Pedir boleia
    Route::post('/ride-requests', [RideController::class, 'requestRide'])
        ->name('ride_requests.store');

    // Minhas boleias pedidas ou oferecidas
    Route::get('/my/requests', [RideController::class, 'myRequests'])
        ->name('ride_requests.my');

    // Cancelar pedido
    Route::delete('/ride-requests/{id}', [RideController::class, 'cancelRequest'])
        ->name('ride_requests.cancel');

    // Aceitar pedido
    Route::post('/ride-requests/{id}/accept', [RideController::class, 'acceptRequest'])
        ->name('ride_requests.accept');

    // Rejeitar pedido
    Route::post('/ride-requests/{id}/reject', [RideController::class, 'rejectRequest'])
        ->name('ride_requests.reject');
});


Route::view('/como-funciona', 'utils.como_funciona')->name('utils.how');
Route::view('/contactos', 'utils.contactos')->name('utils.contact');


/*
|--------------------------------------------------------------------------
| ROTA DE FALLBACK (404)
|--------------------------------------------------------------------------
| Exibe página personalizada quando rota não existe.
*/


Route::fallback(function () {
    return view('utils.fallbackView');
});
