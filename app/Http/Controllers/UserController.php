<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORMULÁRIO PARA ADICIONAR USUÁRIO (APENAS ADMIN)
    |--------------------------------------------------------------------------
    */
    public function addUser()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Apenas administradores podem criar usuários.');
        }

        $pageAdmin = 'Admin CESAE';
        return view('users.add_user', compact('pageAdmin'));
    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR NOVO USUÁRIO
    |--------------------------------------------------------------------------
    | Valida email institucional, senha e role.
    */
    public function storeUser(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Apenas administradores podem criar usuários.');
        }

        $request->validate([
            'name' => 'required|string|max:50',

            // Validação do email institucional CESAE
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
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return redirect()->route('users.all')
            ->with('message', 'Conta CESAE criada com sucesso!');
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR TODOS OS USUÁRIOS (APENAS ADMIN)
    |--------------------------------------------------------------------------
    */
    public function allUsers()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado.');
        }

        // Agora usando Eloquent (mais limpo e seguro)
        $users = User::all();

        return view('users.all_users', compact('users'));
    }


    /*
    |--------------------------------------------------------------------------
    | VER PERFIL DE UM USUÁRIO
    |--------------------------------------------------------------------------
    | Admin pode ver qualquer perfil.
    | Usuário comum só pode ver o próprio.
    */
    public function viewUser($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403, 'Você não pode ver o perfil de outro usuário.');
        }

        return view('users.view_user', compact('user'));
    }


    /*
    |--------------------------------------------------------------------------
    | APAGAR USUÁRIO (APENAS ADMIN)
    |--------------------------------------------------------------------------
    */
    public function deleteUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Apenas administradores podem apagar usuários.');
        }

        $user = User::findOrFail($id);

        // Se quiser impedir admin de apagar a si mesmo:
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode apagar sua própria conta.');
        }

        $user->delete();

        return back()->with('message', 'Usuário deletado com sucesso.');
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PERFIL DO USUÁRIO
    |--------------------------------------------------------------------------
    | Usuário comum só pode editar o próprio perfil.
    | Admin pode editar qualquer perfil.
    */
    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Segurança: impedir edição indevida
        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403, 'Você não pode editar outro usuário.');
        }

        $request->validate([
            'name'            => 'required|string|max:50',
            'photo'           => 'nullable|image|max:2048',
            'whatsapp_phone'  => 'nullable|string|max:20',
            'pickup_location' => 'required|string|max:255',
            'bio'             => 'nullable|string|max:1000',
        ]);

        // Upload da foto
        $photo = $user->photo;
        if ($request->hasFile('photo')) {

            // Apagar foto antiga se existir
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $photo = $request->file('photo')->store('userPhotos', 'public');
        }

        // Atualizar no banco
        $user->update([
            'name'            => $request->name,
            'whatsapp_phone'  => $request->whatsapp_phone,
            'pickup_location' => $request->pickup_location,
            'bio'             => $request->bio,
            'photo'           => $photo,
        ]);

        return redirect()->route('users.view', $user->id)
            ->with('message', 'Usuário atualizado com sucesso.');
    }
}
