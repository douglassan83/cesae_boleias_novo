<?php

namespace App\Http\Controllers;

use App\Models\RideRequest;
use App\Models\Message;

class AdminController extends Controller
{
    public function reopenRequest($id)
{
    // Apenas admin pode reabrir pedidos
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    // Busca o pedido pelo ID enviado pelo botão
    $request = RideRequest::findOrFail($id);

    // Atualiza o status para permitir que o passageiro peça novamente
    $request->status = 'cancelled_by_admin';
    $request->save();

    // Marca a mensagem como resolvida automaticamente
    Message::where('ride_reference', $id)
        ->where('subject', 'pedido_rejeitado')
        ->update(['resolved' => true]);

    // Retorna com mensagem de sucesso
    return back()->with('success', 'Pedido reaberto com sucesso.');
}


}
