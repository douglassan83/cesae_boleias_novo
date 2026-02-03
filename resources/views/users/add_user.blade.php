@extends('layouts.main_layout')
@section('content')
    <br>
    <h5>REGISTAR</h5>
    {{-- <h5>{{ $pageAdmin ?? 'Registo CESAE' }}</h5>
<h4>{{ $pageAdmin ?? 'Criar Conta' ? 'Adicionar' : 'Registar' }}</h4> --}}
    <br>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nome *</label>
            <input name="name" type="text" class="form-control" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Email CESAE *</label>
            <input name="email" type="email" class="form-control" placeholder="nome@msft.cesae.pt" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Senha *</label>
            <input name="password" type="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
            <label>Confirmar Senha *</label>
            <input name="password_confirmation" type="password" class="form-control" required>
        </div>

        @auth
            @if (Auth::check() && Auth::user()->role == 'admin')
                <div class="mb-3">
                    <label>Tipo</label>
                    <select name="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="passenger">Passageiro</option>
                        <option value="driver">Motorista</option>

                    </select>
                </div>
            @endif
        @endauth

        @guest
            <div class="mb-3">
                <label>Objetivo</label>
                <select name="role" class="form-control">
                    <option value="" disabled selected>Selecione o que você deseja</option>
                    <option value="passenger">Preciso de boleia</option>
                    <option value="driver">Quero oferecer boleia</option>
                </select>
            </div>
        @endguest

        <button type="submit" class="btn btn-primary">{{ $pageAdmin ?? 'Registar' ? 'Adicionar' : '' }}</button>

    </form>
@endsection
