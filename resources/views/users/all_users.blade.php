@extends('layouts.main_layout')

@section('content')
<br>
    <div class="container">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">Utilizadores</h3>
                <p class="text-muted mb-0">Gestão de contas CESAE</p>
            </div>

            <a href="{{ route('users.add') }}" class="btn btn-success">
                Adicionar Utilizador
            </a>
        </div>

        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Local de partida</th>
                        <th scope="col">Tipo</th>
                        <th scope="col" class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>

                            <td>
                                <img
                                    width="44"
                                    height="44"
                                    class="rounded-circle shadow-sm"
                                    src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('images/nophoto.jpg') }}"
                                    alt="Foto {{ $user->name }}">
                            </td>

                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->pickup_location ?? '—' }}</td>

                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('users.view', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                    Ver
                                </a>

                                @if (Auth::check() && Auth::user()->role == 'admin')
                                    <a
                                        href="{{ route('users.delete', $user->id) }}"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Apagar utilizador {{ $user->name }}?')"
                                    >
                                        Apagar
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Sem utilizadores para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
@endsection
