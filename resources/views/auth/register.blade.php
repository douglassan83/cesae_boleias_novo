@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <h2 class="mb-4 fw-bold text-center">Criar nova conta</h2>

            {{-- MENSAGENS DE ERRO --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Corrija os erros abaixo:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULÁRIO --}}
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- NOME --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome completo*</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="O meu nome completo"
                                   value="{{ old('name') }}" required>
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail CESAE*</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="user@msft.cesae.pt"
                                   value="{{ old('email') }}" required>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Palavra-passe*</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="********" required>
                        </div>

                        {{-- CONFIRM PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirmação da Palavra-passe*</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="********" required>
                        </div>

                        {{-- ROLE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Motorista / Passageiro*</label>
                            <select name="role" class="form-select" required>
                                <option value="">Selecione...</option>
                                <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Motorista</option>
                                <option value="passenger" {{ old('role') == 'passenger' ? 'selected' : '' }}>Passageiro</option>
                            </select>
                        </div>

                        {{-- TERMOS --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" required>
                            <label class="form-check-label">
                                Aceito os termos de uso
                            </label>
                        </div>

                        {{-- BOTÃO --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            Registar
                        </button>

                    </form>

                </div>
            </div>

        </div>

        {{-- ILUSTRAÇÃO (opcional) --}}
        <div class="col-lg-4 d-none d-lg-block">
            <div class="text-center">
                <img src="{{ asset('images/5937880.jpg') }}"
                     alt="Ilustração"
                     class="img-fluid rounded">
            </div>
        </div>

    </div>

</div>
@endsection
