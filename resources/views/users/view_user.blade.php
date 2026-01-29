@extends('layouts.main_layout')
@section('content')
    <br>

    <div class="container">
        <div class="row">
            {{-- ESQUERDA: INFO USER --}}
            <div class="col-md-4">
                <h4>Perfil do Usuário</h4>

                {{-- FOTO CORRETA --}}
                @if ($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" class="img-fluid rounded-circle mb-3 shadow"
                        style="width: 150px; height: 150px; object-fit: cover;" alt="Foto {{ $user->name }}">
                @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 shadow"
                        style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-secondary"></i>
                    </div>
                @endif


                {{-- DADOS DB --}}
                {{--Botão pedir boleia deve alterar aguardando o aceite do motorista--}}
                <p><strong>name:</strong> {{ $user->name }}</p>
                <p><strong>email:</strong> {{ $user->email }}</p>
                <p><strong>whatsApp_phone:</strong> {{ $user->whatsapp_phone ?? 'Não informado' }}</p>
                <p><strong>pickup_location:</strong> {{ $user->pickup_location ?? 'Não definido' }}</p>
                <p><strong>role:</strong>
                
                    @switch($user->role)
                        @case('driver')
                            Motorista
                        @break

                        @case('passenger')
                            Passageiro
                        @break

                        @default
                            {{ ucfirst($user->role) }}
                    @endswitch
                <p><strong>bio:</strong> {{ $user->bio }}</p>
                </p>
            </div>

            {{-- DIREITA: FORM EDITAR --}}
            <div class="col-md-8">
                <h5>Editar Perfil</h5>

                <form method="POST" action="{{ route('users.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $user->id }}">

                    {{-- NOME --}}
                    <div class="mb-3">
                        <label class="form-label">name</label>
                        <input value="{{ $user->name }}" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- WHATSAPP_PHONE --}}
                    <div class="mb-3">
                        <label class="form-label">whatsapp_phone</label>
                        <input value="{{ $user->whatsapp_phone ?? '' }}" name="whatsapp_phone" type="tel"
                            class="form-control">
                    </div>

                    {{-- PICKUP_LOCATION --}}
                    <div class="mb-3">
                        <label class="form-label">pickup_location</label>
                        <input value="{{ $user->pickup_location ?? '' }}" name="pickup_location" type="text"
                            class="form-control" required>
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-3">
                        <label class="form-label">photo</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        @if ($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                style="width: 30px; height: 30px; object-fit: cover;" alt="Foto {{ $user->name }}">
                            <i>ficheiro de foto carregado (.jpg)</i>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">bio</label>
                        <input value="{{ $user->bio ?? '' }}" name="bio" type="text" class="form-control">

                    </div>


                    {{-- BOTÕES --}}
                    <button type="submit" class="btn btn-primary me-2">Actualizar</button>

                </form>
            </div>
        </div>
    </div>
@endsection
