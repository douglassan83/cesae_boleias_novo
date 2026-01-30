@extends('layouts.main_layout')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="card shadow" style="width: 600px; min-height: 600px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">Registar</h3>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                {{-- NOME --}}
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input name="name" type="text" class="form-control" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label">Email CESAE *</label>
                    <input name="email" type="email" class="form-control"
                           placeholder="nome@msft.cesae.pt" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label">Senha *</label>
                    <input name="password" type="password" class="form-control" required minlength="8">
                </div>

                {{-- CONFIRMAR PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label">Confirmar Senha *</label>
                    <input name="password_confirmation" type="password" class="form-control" required>
                </div>

                {{-- ROLE (ADMIN) --}}
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="role" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="passenger">Passageiro</option>
                                <option value="driver">Motorista</option>
                            </select>
                        </div>
                    @endif
                @endauth

                {{-- ROLE (GUEST) --}}
                @guest
                    <div class="mb-4">
                        <label class="form-label">Objetivo</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Selecione o que você deseja</option>
                            <option value="passenger">Preciso de boleia</option>
                            <option value="driver">Quero oferecer boleia</option>
                        </select>
                    </div>
                @endguest

                {{-- BOTÃO --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        {{ Auth::check() && Auth::user()->role === 'admin' ? 'Adicionar' : 'Registar' }}
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
