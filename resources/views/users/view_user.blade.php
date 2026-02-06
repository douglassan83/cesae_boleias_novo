@extends('layouts.main_layout')
@section('content')
    <br>


    <div class="container">
        <div class="row">
            {{-- ESQUERDA: INFO USER --}}
            <div class="col-md-4">
                <h4>Perfil do Usuário</h4>
                @if ($user->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $user->photo) }}" class="img-fluid rounded-circle mb-3 shadow"
                          style="width: 150px; height: 150px; object-fit: cover;" alt="Foto {{ $user->name }}">

                    </div>
                @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 shadow"
                        style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-secondary"></i>
                    </div>

                @endif



                {{-- DADOS DB --}}
                {{-- Botão pedir boleia deve alterar aguardando o aceite do motorista --}}
                <p><strong>Nome:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Telefone:</strong> {{ $user->whatsapp_phone ?? 'Não informado' }}</p>
                <p><strong>Ponto de partida:</strong> {{ $user->pickup_location ?? 'Não definido' }}</p>
                <p><strong>Objetivo:</strong>

                    @switch($user->role)
                        @case('driver')
                            Motorista
                        @break

                        @case('passenger')
                            Passageiro
                        @break

                        @default
                            {{ ucfirst($user->role) }} {{-- método pra inserir maiuscula na primeira letra --}}
                    @endswitch
                <p><strong>bio:</strong> {{ $user->bio }}</p>
                </p>
            </div>

            {{-- DIREITA: FORM EDITAR --}}
            <div class="col-md-8">
                <h5>Editar Perfil</h5>
                {{-- mensagem usuário adicionado com sucesso --}}
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert"">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('users.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $user->id }}">

                    {{-- NOME --}}
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input value="{{ $user->name }}" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WHATSAPP_PHONE --}}
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input value="{{ $user->whatsapp_phone ?? '' }}" name="whatsapp_phone" type="tel"
                            class="form-control">
                    </div>

                    {{-- PICKUP_LOCATION --}}
<div class="mb-3 position-relative">
    <label class="form-label fw-bold">Ponto de Partida</label>

    <input type="text"
           name="pickup_location"
           id="pickup_location"
           class="form-control @error('pickup_location') is-invalid @enderror"
           placeholder="Escolha um ponto de partida:"
           autocomplete="off"
           required
           value="{{ old('pickup_location', auth()->user()->pickup_location ?? '') }}">

    <ul id="pickup_list" class="list-group suggestion-list d-none"></ul>

    @error('pickup_location')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror


</div>


                    {{-- FOTO --}}
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        @if ($user->photo)
                         <i>ficheiro de foto carregado com sucesso (.png .jpg max 2MB)</i>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">sobre mim</label>
                        <input value="{{ $user->bio ?? '' }}" name="bio" type="text" class="form-control">

                    </div>


                    {{-- BOTÕES --}}
                    <button type="submit" class="btn btn-primary ">Actualizar</button>
                    <a href="{{ route('rides.all') }}" class="btn btn-secondary ">Voltar</a>
                </form>
                <br>

                <br>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/scrollCity.js') }}"></script>
@endpush
