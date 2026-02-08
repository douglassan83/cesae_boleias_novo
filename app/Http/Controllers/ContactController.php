<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Guardar mensagem public
    function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required', ]);

    ContactMessage::create($request->all());

    return back()->with('success', 'Mensagem enviada com sucesso!'); }

    // Página do admin com todas as mensagens
    public function index() {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();

        return view('admin.messages.index', compact('messages')); }


        public function resolve($id)
{
    $msg = ContactMessage::findOrFail($id);
    $msg->resolved = true;
    $msg->save();

    return back()->with('success', 'Mensagem marcada como resolvida.');
}

}
