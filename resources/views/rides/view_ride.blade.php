@extends('layouts.main_layout')
@section('content')
    <div class="container">
        <br>
        <h3>Boleia #{{ $ride->id }}</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                {{-- Motorista --}}
                <h5>Motorista: {{ $ride->driver->name ?? 'N/A' }}</h5>

                {{-- ida e volta  --}}
                <p><strong>Origem:</strong> {{ $ride->pickup_location ?? 'N/A' }}</p>
                <p><strong>Destino:</strong> {{ $ride->destination_location ?? 'N/A' }}</p>

                {{-- Data --}}
                <p><strong>Data:</strong> {{ $ride->departure_date ? $ride->departure_date->format('d/m/Y') : 'N/A' }}</p>

                {{-- Hora --}}
                <p><strong>Hora:</strong> {{ $ride->departure_time ? $ride->departure_time->format('H:i') : 'N/A' }}</p>

                {{-- Lugares --}}
                <p><strong>Lugares:</strong> {{ $ride->available_seats ?? 0 }} / {{ $ride->total_seats ?? 0 }}</p>

                {{-- Observações --}}
                @if ($ride->observations)
                    <p><strong>Observações:</strong> {{ $ride->observations }}</p>
                @endif

                {{-- Status (inglês DB) --}}
                <p><strong>Status:</strong>
                    <span class="badge {{ $ride->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $ride->status == 'active' ? 'Ativa' : ucfirst($ride->status) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- BOTÕES --}}
        <div class="mt-3">
            @auth
                @if (auth()->id() == $ride->driver_id)
                    <a href="{{ route('rides.edit', $ride) }}" class="btn btn-warning"> <i class="fas fa-edit"></i> Editar</a>
                    <a href="{{ route('rides.delete', $ride->id) }}" class="btn btn-sm btn-danger"
                        onclick="return confirm('Cancelar esta boleia?')">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                @endif

                {{-- PASSAGEIRO: só se disponível --}}
                @if (auth()->id() != $ride->driver_id && ($ride->available_seats ?? 0) > 0 && $ride->status == 'active')
                    <form method="POST" action="#" class="d-inline">
                        @csrf
                        <input type="hidden" name="ride_id" value="{{ $ride->id }}">
                        <button type="submit" class="btn btn-primary ">Pedir Boleia </button>
                    </form>
                @endif
            @endauth

            <a href="{{ route('rides.all') }}" class="btn btn-secondary">← Voltar</a>
        </div>
    </div>
@endsection
