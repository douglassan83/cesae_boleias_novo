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
        'photo' => 'nullable|image|max:2048',
        'whatsapp_phone' => 'nullable|string|max:20',
        'pickup_location' => 'required|string|max:255',
        'bio' => 'nullable|string|max:1000',
    ]);

    $photo = $request->photo_path ?? null;
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo')->store('userPhotos', 'public');
    }

    DB::table('users')
        ->where('id', $request->id)
        ->update([
            'name' => $request->name,
            'whatsapp_phone' => $request->whatsapp_phone,
            'pickup_location' => $request->pickup_location,
            'bio' => $request->bio,
            'photo' => $photo,
            'updated_at' => now(),
        ]);

    return redirect()->route('users.view', $request->id)->with('message', 'Usuário atualizado com sucesso');
}

}
