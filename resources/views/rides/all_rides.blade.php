@extends('layouts.main_layout')
@section('content')
    @php use App\Models\RideRequest; @endphp

    <div class="container mt-4 page-section">
        {{-- TÍTULO DINÂMICO POR ROLE --}}
        <h3 class="page-title mb-1">
            @if (auth()->user()->role == 'driver')
                Minhas Boleias oferecidas (Motorista)
            @elseif(auth()->user()->role == 'passenger')
                Procurar Boleias perto de:
                {{ auth()->user()->pickup_location ?? 'sem ponto de partida' }}
            @else
                Todas as Boleias (Admin)
            @endif
        </h3>
        <p class="page-subtitle">
            @if (auth()->user()->role == 'driver')
                Gere as tuas boleias ativas e responde aos pedidos.
            @elseif(auth()->user()->role == 'passenger')
                Encontra boleias compatíveis com o teu ponto de partida.
            @else
                Visão geral de todas as boleias da plataforma.
            @endif
        </p>

        {{-- BOTões --}}

        @auth
            {{-- ADMIN: vê 3 botões --}}
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">
                    Adicionar Boleia
                </a>
                <a href="{{ route('rides.my_requests') }}" class="btn btn-primary mb-3">
                    Pedidos Recebidos
                </a>
                <a href="{{ route('rides.my_requests') }}" class="btn btn-warning mb-3">
                    Pedidos Solicitados
                </a>

                {{-- DRIVER: 2 botões --}}
            @elseif (auth()->user()->role == 'driver')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">
                    Adicionar Boleia
                </a>
                <a href="{{ route('rides.my_requests') }}" class="btn btn-primary mb-3">
                    Pedidos Recebidos
                </a>

                {{-- PASSENGER: 1 botão --}}
            @elseif (auth()->user()->role == 'passenger')
                <a href="{{ route('rides.my_requests') }}" class="btn btn-primary mb-3">
                    Pedidos Solicitados
                </a>
            @endif
        @endauth


        {{-- ALERTAS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- TABELA BOLEIAS --}}
        <div class="row">

            @forelse($rides as $ride)
                @php
                    $myRequest = $ride->rideRequests
                        ->where('passenger_id', auth()->id())
                        ->where('status', 'accepted')
                        ->first();
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

                            @if ($ride->status === 'active')
                                <span class="badge bg-success">🟢 Ativa</span>
                            @elseif ($ride->status === 'full')
                                <span class="badge bg-secondary">🔴 Lotada</span>
                            @else
                                <span class="badge bg-danger">❌ {{ ucfirst($ride->status) }}</span>
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
                                📅 {{ date('d/m/Y', strtotime($ride->departure_date)) }}
                                ⏰ {{ date('H:i', strtotime($ride->departure_time)) }}
                            </p>

                            <span class="badge bg-primary">
                                👥 {{ $ride->available_seats }} / {{ $ride->total_seats }} lugares
                            </span>


                        </div>

                        {{-- FOOTER --}}
                        <div class="ride-card-footer">

                            {{-- MOTORISTA --}}
                            @if (auth()->id() === $ride->driver_id)
                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info">
                                    Ver
                                </a>

                                {{-- PASSAGEIRO --}}
                            @elseif (auth()->user()->role === 'passenger')
                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">
                                    Ver boleia
                                </a>
                            @endif

                            {{-- botao teams --}}

                            @if ($myRequest && $myRequest->teams_link)
                                <a href="{{ $myRequest->teams_link }}" target="_blank"
                                    title="Abrir reunião no Microsoft Teams" class="teams-btn mt-2">

                                    <i class="bi bi-microsoft-teams"> Teams</i>

                                </a>
                            @endif


                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-car-front fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">
                        Nenhuma boleia disponível
                    </h5>
                    @if (Auth::user()->role != 'driver' && auth()->user()->pickup_location == '')
                        <div class="alert alert-danger"">
                            <h5>
                                <i>
                                'Atenção: preencha ponto de partida no perfil!' </i>
                            </h5>
                        </div>
                    @endif
                </div>
        </div>
        @endforelse

    </div>

    </div>
@endsection
