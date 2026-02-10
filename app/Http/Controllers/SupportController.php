<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class SupportController extends Controller
{
    public function form($ride_id, $request_id)
{
    return view('support.form', compact('ride_id', 'request_id'));
}


    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'ride_id' => 'nullable|integer'
        ]);

        ContactMessage::create([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'subject' => 'pedido_rejeitado',
            'message' => $request->message,
            'ride_reference' => $request->request_id,
            'resolved' => false
        ]);

        return redirect()->route('rides.all')
            ->with('success', 'Mensagem enviada ao suporte.');
    }
}
