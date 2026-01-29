@extends('layouts.main_layout')

@section('content')
    <div class="container">
        <br>

        <h3>Boleia #{{ $ride->id }}</h3>

        @auth
            @php
                $pedido = \App\Models\RideRequest::where('ride_id', $ride->id)
                    ->where('passenger_id', auth()->id())
                    ->whereIn('status', ['pending', 'accepted'])
                    ->first();
            @endphp
        @endauth


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- CARD DA BOLEIA --}}
        <div class="card">
            <div class="card-body">

                <h5>Motorista: {{ $ride->driver->name ?? 'N/A' }}</h5>

                <p><strong>Origem:</strong> {{ $ride->pickup_location ?? 'N/A' }}</p>
                <p><strong>Destino:</strong> {{ $ride->destination_location ?? 'N/A' }}</p>

                <p><strong>Data:</strong>
                    {{ $ride->departure_date ? $ride->departure_date->format('d/m/Y') : 'N/A' }}
                </p>

                <p><strong>Hora:</strong>
                    {{ $ride->departure_time ? $ride->departure_time->format('H:i') : 'N/A' }}
                </p>

                <p><strong>Lugares:</strong>
                    {{ $ride->available_seats ?? 0 }} / {{ $ride->total_seats ?? 0 }}
                </p>

                @if ($ride->observations)
                    <p><strong>Observações:</strong> {{ $ride->observations }}</p>
                @endif

                <p><strong>Status:</strong>
                    <span class="badge {{ $ride->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $ride->status === 'active' ? 'Ativa' : ucfirst($ride->status) }}
                    </span>
                </p>

                @if (auth()->check() && $pedido)
                    <hr>

                    <form method="POST" action="{{ route('ride_requests.cancel', $pedido->id) }}" class="d-inline"
                        onsubmit="return confirm('Tem a certeza que deseja cancelar o pedido?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm">
                            ❌ Cancelar Pedido
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- BOTÕES --}}
        <div class="mt-3">

            @auth
                {{-- MOTORISTA --}}
                @if (auth()->id() === $ride->driver_id)
                    {{-- EDITAR --}}
                    <a href="{{ route('rides.edit', $ride->id) }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>

                    {{-- Excluir --}}
                    <form action="{{ route('rides.delete', $ride->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Excluir esta boleia?')">
                        @csrf
                        @method('DELETE')

                {{-- ========================================================= --}}
                {{-- ADICIONADO: Mostrar dados do motorista quando pedido ACEITE --}}
                {{-- ========================================================= --}}
                @if (auth()->check() && $pedido && $pedido->status === 'accepted')
                    <hr>

                    <h5 class="text-success">✔ O motorista aceitou o seu pedido!</h5>

                    <p><strong>Motorista:</strong> {{ $ride->driver->name }}</p>
                    <p><strong>Email:</strong> {{ $ride->driver->email }}</p>
                    <p><strong>Telefone:</strong> {{ $ride->driver->phone ?? 'Não disponível' }}</p>

                    @if ($pedido->teams_link)
                        <a href="{{ $pedido->teams_link }}" target="_blank" class="btn btn-success mt-2">
                            🎥 Entrar no Teams
                        </a>
                    @endif
                @endif
                {{-- ========================================================= --}}

            </div>
        </div>
    </div>
@endsection
