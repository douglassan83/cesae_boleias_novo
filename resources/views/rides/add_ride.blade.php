{{-- ========================================
   add_ride.blade.php - CRIAR BOLEIA - role: driver=MOTORISTA
   ======================================== --}}
@extends('layouts.main_layout')
@section('content')


    <div class="container mt-4">
        <h3 class="mb-4">Criar Boleia CESAE </h3>

        {{-- ALERTAS --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Erros:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CRIAR BOLEIA --}}
        <form method="POST" action="{{ route('rides.store') }}">
            @csrf

            {{-- 1. ORIGEM --}}
            <div class="mb-3 position-relative">
                <label class="form-label fw-bold">Origem (Casa/Ponto Partida)</label>

                <input type="text" name="pickup_location" id="pickup_location"
                    class="form-control @error('pickup_location') is-invalid @enderror"
                    placeholder="Ex: São João da Madeira" autocomplete="off" required>

                <ul id="pickup_list" class="list-group suggestion-list d-none"></ul>

                @error('pickup_location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- 2. DESTINO --}}
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



            {{-- 3. DATA --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Data Partida</label>
                <input name="departure_date" type="date"
                    class="form-control @error('departure_date') is-invalid @enderror"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                @error('departure_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 4. HORA --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Hora Partida</label>
                <input name="departure_time" type="time"
                    class="form-control @error('departure_time') is-invalid @enderror" required>
                @error('departure_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 5. LUGARES --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Lugares Totais (1-8)</label>
                <input name="total_seats" type="number" min="1" max="8"
                    class="form-control @error('total_seats') is-invalid @enderror" value="4" required>
                @error('total_seats')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- 6. OBSERVAÇÕES (DB: observations) --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Observações (Opcional)</label>
                <textarea name="observations" rows="3" class="form-control @error('observations') is-invalid @enderror"
                    placeholder="Ex: Ponto de referência , valor para dividir de combustível..."></textarea>
                @error('observations')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- BOTÕES --}}
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="btn btn-primary btn-lg">
                    Publicar Boleia
                </button>
                <a href="{{ route('rides.all') }}" class="btn btn-secondary btn-lg">
                    ← Voltar Lista Boleias
                </a>
            </div>
        </form>
    </div>

@endsection


@push('scripts')
    <script src="{{ asset('js/scrollCity.js') }}"></script>
@endpush
