<?php

use App\Http\Controllers\TermsController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilController;
use App\Http\Controllers\ContactController;


//teste de react na pasta resources/js
Route::get('/react-test', function () {
    return Inertia::render('Welcome');
});




Route::get('/', [UtilController::class, 'home'])
    ->name('utils.welcome'); //dar nome para a rota

Route::get('/hello', function () {
    return view('utils.helloView');
})->name('utils.hello'); //dar nome para a rotanp

Route::get('/turma/{nomeTurma}', function ($nomeTurma) {
    //ir a base de dados e buscar a info da turma
    return view('utils.turmaView', compact('nomeTurma'));
})->name('turma.name');


// pagina contatos e como funciona
Route::view('/como-funciona', 'utils.como_funciona')->name('utils.how');
Route::view('/contactos', 'utils.contactos')->name('utils.contact');

//rota para termos de responsabilidades e normas para confirmar antes do registo
Route::get('/terms', [TermsController::class, 'terms'])->name('utils.terms');

//pagina de mensagens (ADMIN)
Route::view('/index', 'admin.index')->name('admin.messages');


//ROTAS DO USER

//abre a listagem de todos os usuario
Route::get('/allusers', [UserController::class, 'allUsers'])->name('users.all')->middleware('auth');

//rota que abre a view com toda a info do user
Route::get('/viewuser/{id}', [UserController::class, 'viewUser'])->name('users.view');

//rota GET para visualizar o formulário vazio para inserir novo user
Route::get('/addUsers', [UserController::class, 'addUser'])->name('users.add');

//rota POST que pega os dados do formulário e envia para o servidor
Route::post('/store-user', [UserController::class, 'storeUser'])->name('users.store');

//roto para atualizar o usuario
Route::put('/updateUser', [UserController::class, 'updateUser'])->name('users.update');

//rota que apaga o user
Route::get('/deleteuser/{id}', [UserController::class, 'deleteUser'])->name('users.delete');







// RIDES - GERENCIAR AS BOLEIAS CESAE
Route::middleware('auth')->group(function () {
    Route::get('/rides/add', [RideController::class, 'addRide'])->name('rides.add');
    Route::post('/rides', [RideController::class, 'storeRide'])->name('rides.store');
    Route::get('/rides', [RideController::class, 'allRides'])->name('rides.all');
});


// rotas pedidos das boleias
Route::middleware('auth')->group(function () {

    // cancelar pedido de boleia
    Route::delete('/ride-requests/{id}', [RideController::class, 'cancelRequest'])
        ->name('ride_requests.cancel');

    // pedir boleia
    Route::post('/rides/request', [RideController::class, 'requestRide'])->name('rides.request');

    // visualizar as boleias pedidas ou oferecidas
    Route::get('/rides/requests', [RideController::class, 'myRequests'])->name('rides.my_requests');

    // Info da boleia(ver) + Cancelar(excluir de all_rides e mudar status na my_requests(minhas boleias))
    Route::get('/rides/{ride}', [RideController::class, 'viewRide'])->name('rides.view');
    Route::delete('/rides/{ride}', [RideController::class, 'deleteRide'])->name('rides.delete');

    // + EDITAR boleia oferecida
    Route::get('/rides/{ride}/edit', [RideController::class, 'editRide'])->name('rides.edit');
    Route::put('/rides/{ride}', [RideController::class, 'updateRide'])->name('rides.update');

    // aceitar boleia / rejeitar pedido (sempre com ID numérico do pedido)
    Route::post('/ride-requests/{id}/accept', [RideController::class, 'acceptRequest'])
        ->name('ride_requests.accept');

    //  rejeitar pedido

    Route::post('/ride-requests/{id}/reject', [RideController::class, 'rejectRequest'])
        ->name('ride_requests.reject');
});

// Envio de mensagens
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

// Página do admin (proteger com middleware admin)
Route::get('/admin/messages', [ContactController::class, 'index'])
    ->middleware(\App\Http\Middleware\IsAdmin::class)
    ->name('admin.messages');

Route::post('/admin/messages/{id}/resolve', [ContactController::class, 'resolve'])
    ->middleware(\App\Http\Middleware\IsAdmin::class)
    ->name('admin.messages.resolve');







// rota de erro na rota (pagina 404)para guiar o usuario de volta a home
Route::fallback(function () {
    return view('utils.fallbackView');
});
