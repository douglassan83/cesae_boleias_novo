@extends('layouts.main_layout')

@section('title', 'Editar Boleia')

@section('content')
    <div class="container page-section">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card form-card">
                    <div class="card-header">
                        <h4>Editar Boleia #{{ $ride->id }}</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('rides.update', $ride) }}">
                            @csrf
                            @method('PUT')

                            <!-- Campo: Origem -->
                            <div class="mb-3">
                                <label for="pickup_location" class="form-label">Origem</label>
                                <input type="text" class="form-control @error('pickup_location') is-invalid @enderror"
                                    id="pickup_location" name="pickup_location"
                                    value="{{ old('pickup_location', $ride->pickup_location) }}" required>
                                @error('pickup_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Campo: Destino -->
                            <div class="mb-3">
                                <label for="destination_location" class="form-label">Destino</label>
                                <input type="text"
                                    class="form-control @error('destination_location') is-invalid @enderror"
                                    id="destination_location" name="destination_location"
                                    value="{{ old('destination_location', $ride->destination_location) }}" required>
                                @error('destination_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Campo: DATA --}}
                            <div class="mb-3">
                                <label for="departure_date" class="form-label">Data da Partida</label>
                                <input type="date" class="form-control @error('departure_date') is-invalid @enderror"
                                    id="departure_date" name="departure_date"
                                    value="{{ old('departure_date', $ride->departure_date?->format('Y-m-d')) }}" required>
                                @error('departure_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Campo: HORA --}}
                            <div class="mb-3">
                                <label for="departure_time" class="form-label">Hora da Partida</label>
                                <input type="time" class="form-control @error('departure_time') is-invalid @enderror"
                                    id="departure_time" name="departure_time"
                                    value="{{ old('departure_time', $ride->departure_time?->format('H:i')) }}" required>
                                @error('departure_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Campo: Lugares disponíveis -->
                            <div class="mb-3">
                                <label for="available_seats" class="form-label">Lugares Disponíveis</label>
                                <input type="number" min="1" max="7"
                                    class="form-control @error('available_seats') is-invalid @enderror" id="available_seats"
                                    name="available_seats" value="{{ old('available_seats', $ride->available_seats) }}"
                                    required>
                                @error('available_seats')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botões -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Atualizar Boleia
                                </button>
                                <a href="{{ route('rides.view', $ride) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar edição
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
 