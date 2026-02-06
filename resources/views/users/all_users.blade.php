@extends('layouts.main_layout')
@section('content')
    <div class="container mt-4">

        {{-- botão add user --}}
        <a href="{{ route('users.add') }}" class="btn btn-success"> Adicionar Usuário</a>
        <br>
        {{-- mensagem usuário adicionado com sucesso --}}
        @if (session('message'))
            <div class="">
                {{ session('message') }}
            </div>
        @endif
        <br>



        {{-- tabela BOOTSTRAP (TABLE ROW) --}}

        {{-- mantido os titulos das colunas com o mesmo nome da DB provisoriamente --}}
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">photo</th>
                    <th scope="col">name</th>
                    <th scope="col">email</th>
                    <th scope="col">pickup_location</th>
                    {{-- <th scope="col">Telefone</th> --}}
                    <th scope="col">role</th>

                    <th></th>
                    <th></th>


                </tr>
            </thead>
            <tbody>
                {{-- {{ dd($users) }} usado para verificar no navegador qual é a forma que os dados estão vindo antes de carrega-los --}}
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td><img width="50px" height="50px" class="img-fluid rounded-circle mb-3 shadow"
                                src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/nophoto.jpg') }}"
                                alt=""></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->pickup_location }}</td>
                        {{-- <td>{{ $user->phone}}</td> --}}
                        <td>{{ $user->role }}</td>


                        @auth
                            <td><a href="{{ route('users.view', $user->id) }}" class="btn btn-info">Ver</a></td>

                            @if (Auth::user()->role == 'admin')
    <td>
        <a href="{{ route('users.delete', $user->id) }}"
           class="btn btn-danger"
           onclick="return confirm('Tem certeza que deseja apagar este usuário? Esta ação não pode ser desfeita.')">
           Apagar
        </a>
    </td>
@endif

                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
