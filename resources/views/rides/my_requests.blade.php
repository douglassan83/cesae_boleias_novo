@extends('layouts.main_layout')

@section('content')
    <div class="container page-section">
        <h3 class="page-title">{{ $pageTitle }}</h3>
        <p class="page-subtitle">Acompanha o estado dos teus pedidos e boleias.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif
        {{-- LISTA DE PEDIDOS --}}
        <div class="row">
            @forelse ($requests as $request)
                @php
                    $ride = $request->ride;
                @endphp

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="ride-card">

                        {{-- HEADER --}}
                        <div class="ride-card-header">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $ride->driver->photo ? asset('storage/' . $ride->driver->photo) : asset('images/nophoto.png') }}"
                                    class="ride-avatar">
                                <strong>{{ $ride->driver->name }}</strong>
                            </div>

                            {{-- STATUS DO PEDIDO --}}
                            @if ($request->status === 'pending')
                                <span class="badge bg-warning">🟡 Pendente</span>
                            @elseif ($request->status === 'accepted')
                                <span class="badge bg-success">✅ Aceite</span>
                            @else
                                <span class="badge bg-danger">❌ Rejeitado</span>
                            @endif
                        </div>

                        {{-- BODY --}}
                        <div class="ride-card-body">

                            <p class="fw-semibold mb-2">
                                📍 {{ $ride->pickup_location }}
                                <span class="mx-1">→</span>
                                {{ $ride->destination_location }}
                            </p>

                            <p class="mb-1">
                                📅 {{ optional($ride->departure_date)->format('d/m/Y') }}
                                ⏰ {{ optional($ride->departure_time)->format('H:i') }}
                            </p>

                            <span class="badge bg-info">
                                👥 {{ $ride->available_seats }} / {{ $ride->total_seats }} lugares
                            </span>

                            {{-- TEAMS (SÓ SE ACEITE) --}}
                            @if ($request->status === 'accepted' && $request->teams_link)
                                <a href="{{ $request->teams_link }}" target="_blank"
                                    title="Abrir reunião no Microsoft Teams" class="teams-btn mt-3">

                                    <i class="bi bi-microsoft-teams"></i>
                                    Entrar no Teams
                                </a>
                            @endif

                        </div>

                        {{-- FOOTER --}}
                        <div class="ride-card-footer">

                            <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">
                                Ver boleia
                            </a>

                            {{-- CANCELAR PEDIDO (se ainda não rejeitado) --}}
                            @if ($request->status !== 'rejected')
                                <form method="POST" action="{{ route('ride_requests.cancel', $request->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Cancelar
                                    </button>
                                </form>
                            @endif

                        </div>

                    </div>
                </div>

            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-car-front fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">
                        Ainda não pediste nenhuma boleia
                    </h5>
                </div>
            @endforelse
        </div>

        <a href="{{ route('rides.all') }}" class="btn btn-secondary">← Voltar às boleias</a>
    </div>
@endsection
 