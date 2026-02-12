@extends('layouts.main_layout')
@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                    {{-- Card Principal --}}
                    <div class="card shadow-lg border-0 rounded-4">
                        <div id="register-header" class="card-header text-white text-center py-4 rounded-top-4">
                            <h5 class="mb-0 fw-bold">REGISTAR</h5>
                            <div class="mt-2">

                            </div>
                        </div>

                        <div class="card-body p-4 p-md-5">

                            {{-- Alertas --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            {{-- Formulário --}}
                            <form method="POST" action="{{ route('users.store') }}">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Nome *</label>
                                    <input name="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Email CESAE *</label>
                                    <input name="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           placeholder="nome@msft.cesae.pt" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Senha *</label>
                                        <input name="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                               required minlength="8">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Confirmar Senha *</label>
                                        <input name="password_confirmation" type="password" class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                @auth
                                    @if (Auth::check() && Auth::user()->role == 'admin')
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Tipo</label>
                                            <select name="role" class="form-select form-select-lg">
                                                <option value="admin">Admin</option>
                                                <option value="passenger">Passageiro</option>
                                                <option value="driver">Motorista</option>
                                            </select>
                                        </div>
                                    @endif
                                @endauth

                                @guest
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Objetivo</label>
                                        <select name="role" class="form-select form-select-lg">
                                            <option value="" disabled selected>Selecione o que você deseja</option>
                                            <option value="passenger">Preciso de boleia</option>
                                            <option value="driver">Quero oferecer boleia</option>
                                        </select>
                                    </div>
                                @endguest

                                <div class="mb-4 form-check">
                                    <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                                    <label for="terms" class="form-check-label fw-semibold">
                                        Aceito os
                                        <a href="{{ route('utils.terms') }}" target="_blank" class="text-decoration-none fw-bold">
                                            <small>Termos de Uso e Responsabilidade</small>
                                        </a>
                                    </label>
                                    @error('terms')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button id="register-button" type="submit" class="btn text-white btn-lg fw-semibold py-3">
                                        {{ $pageAdmin ?? 'Registar' ? 'Adicionar' : '' }} Usuário
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                    {{-- Link voltar --}}
                    <div class="text-center mt-4">
                        <a href="{{ route('rides.all') }}" class="btn btn-outline-secondary btn-sm">
                            ← Voltar às Boleias
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
