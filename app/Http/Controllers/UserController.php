<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function addUser()
    {
        $pageAdmin = 'Admin CESAE';
        return view('users.add_user', compact('pageAdmin'));
    }

    public function storeUser(Request $request)
    {
   // VALIDAÇÃO DO EMAIL DO CESAE

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@msft.cesae.pt')) {
                        $fail('Email deve terminar em @msft.cesae.pt');
                    }
                }
            ],
            'password' => 'required|min:8|string|confirmed',
            'role' => 'required|in:admin,driver,passenger'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('users.all')->with('message', 'Conta CESAE criada!');
    }

    public function allUsers()
    {
        // Bloqueia acesso se não for admin
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'Acesso negado.');
    }

    $users = DB::table('users')->get();

    return view('users.all_users', compact('users'));
    }

    public function viewUser($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return view('users.view_user', compact('user'));
    }

    public function deleteUser($id)
    {
        // DB::table('tasks')  //
        //     ->where('user_id', $id)
        //     ->delete();

        DB::table('users')  //
            ->where('id', $id)
            ->delete();

        return back()->with('message', 'Usuário deletado com sucesso');
    }

   public function updateUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:50',
        'photo' => 'nullable|image|max:2048',           // Opcional
        'whatsapp_phone' => 'nullable|string|max:20',
        'pickup_location' => 'required|string|max:255',
        'bio' => 'nullable|string|max:1000',
    ]);

    $user = User::findOrFail($request->id);  // Busca pelo ID

    // 🔥 FOTO: só muda SE novo ficheiro enviado
    if ($request->hasFile('photo')) {
        // Apaga foto antiga (se existir)
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        // Salva nova
        $user->photo = $request->file('photo')->store('userPhotos', 'public');
    }
    // Se NÃO tem ficheiro novo → mantém a foto antiga ($user->photo)

    // Atualiza outros campos
    $user->name = $request->name;
    $user->whatsapp_phone = $request->whatsapp_phone;
    $user->pickup_location = $request->pickup_location;
    $user->bio = $request->bio;

    $user->save();

    return redirect()->back()->with('message', 'Perfil atualizado com sucesso!');
}


}
