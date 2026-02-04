{{--CRIAR BOLEIA - role: driver=MOTORISTA
    --}}
@extends('layouts.main_layout')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow form-card" style="width: 700px; min-height: 600px;">
            <div class="card-body p-4">

                <h3 class="text-center mb-4">Criar Boleia CESAE</h3>

                {{-- A PARTIR DAQUI é o TEU FORMULÁRIO ORIGINAL --}}
                <form method="POST" action="{{ route('rides.store') }}">
                    @csrf

                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold">Origem (Casa/Ponto Partida)</label>

                        {{-- Local de Recolha --}}
                        <input type="text" name="pickup_location" id="pickup_location"
                            class="form-control @error('pickup_location') is-invalid @enderror"
                            placeholder="Ex: São João da Madeira" autocomplete="off" required>

                        <ul id="pickup_list" class="list-group suggestion-list d-none"></ul>

                        @error('pickup_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Destino --}}
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold">Destino</label>

                        <input type="text" name="destination_location" id="destination_location"
                            class="form-control @error('destination_location') is-invalid @enderror"
                            placeholder="Ex: CESAE Digital SJ Madeira" autocomplete="off" required>

                        <ul id="destination_list" class="list-group suggestion-list d-none"></ul>

                        @error('destination_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Data --}}
                    <div class="mb-3">
                        <label class="form-label">Data Partida</label>
                        <input type="date" name="departure_date" class="form-control" required>
                    </div>

                    {{-- Hora --}}
                    <div class="mb-3">
                        <label class="form-label">Hora Partida</label>
                        <input type="time" name="departure_time" class="form-control" required>
                    </div>

                    {{-- Lugares --}}
                    <div class="mb-3">
                        <label class="form-label">Lugares Totais (1–4)</label>
                        <input type="number" name="total_seats" class="form-control" min="1" max="4"
                            required>
                    </div>

                    {{-- Observações --}}
                    <div class="mb-4">
                        <label class="form-label">Observações (Opcional)</label>
                        <textarea name="observations" rows="3" class="form-control"
                            placeholder="Ex: ponto de referência, dividir combustível..."></textarea>
                    </div>

                    {{-- BOTÃO PUBLICAR --}}
                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-primary">
                            Publicar Boleia
                        </button>
                    </div>

                    {{-- VOLTAR --}}
                    <div class="text-center">
                        <a href="{{ route('rides.all') }}" class="btn btn-secondary btn-sm">
                            ← Voltar Lista Boleias
                        </a>
                    </div>

                </form>
                {{-- FIM DO FORMULÁRIO --}}

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/scrollCity.js') }}"></script>
@endpush
 