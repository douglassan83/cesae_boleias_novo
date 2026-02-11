@extends('layouts.main_layout')

@section('content')
<br>
    <div class="container page-section">

        <h3 class="page-title">{{ $pageTitle }}</h3>

        @auth
            @if (auth()->user()->role === 'driver')
                <p class="page-subtitle">Vê as tuas boleias desta semana.</p>
            @else
                <p class="page-subtitle">Acompanha o estado dos teus pedidos e boleias.</p>
            @endif
        @endauth

        @guest
            <p class="page-subtitle">Acompanha o estado dos teus pedidos e boleias.</p>
        @endguest

        {{-- ALERTAS --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        {{-- LISTA --}}
        @auth
            @if (auth()->user()->role === 'driver')
                {{-- MOTORISTA: BOLEIAS DA SEMANA --}}
                <div class="row">
                    @forelse ($weeklyRides as $ride)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="ride-card">

                                {{-- HEADER --}}
                                <div class="ride-card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $ride->driver->photo ? asset('storage/' . $ride->driver->photo) : asset('images/nophoto.png') }}"
                                             class="ride-avatar">
                                        <strong>{{ $ride->driver->name }}</strong>
                                    </div>
                                    <h6>Boleia #{{ $ride->id }}</h6>

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

                                    {{-- PEDIDOS MOTORISTA --}}
                                    @php
                                        $pedidos = $ride->rideRequests()->latest()->get();
                                        $ultimos = [];
                                        $pendentes = 0; $aceites = 0; $recusados = 0;

                                        foreach($pedidos as $pedido) {
                                            $ultimos[$pedido->passenger_id] = $pedido->status;
                                        }
                                        foreach($ultimos as $status) {
                                            if($status == 'pending') $pendentes++;
                                            if($status == 'accepted') $aceites++;
                                            if($status == 'rejected') $recusados++;
                                        }
                                    @endphp>

                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>📬 Pedidos:</strong>
                                            @if (count($ultimos) == 0)
                                                <span class="badge bg-secondary ms-2">Nenhum pedido</span>
                                            @else
                                                @if ($pendentes > 0)
                                                    <span class="badge bg-warning text-dark ms-1">{{ $pendentes }} Pendente{{ $pendentes != 1 ? 's' : '' }}</span>
                                                @endif
                                                @if ($aceites > 0)
                                                    <span class="badge bg-success ms-1">{{ $aceites }} Aceite{{ $aceites != 1 ? 's' : '' }}</span>
                                                @endif
                                                @if ($recusados > 0)
                                                    <span class="badge bg-danger ms-1">{{ $recusados }} Recusado{{ $recusados != 1 ? 's' : '' }}</span>
                                                @endif
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                {{-- FOOTER --}}
                                <div class="ride-card-footer">
                                    <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info">
                                        Ver
                                    </a>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-car-front fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Ainda não tens boleias esta semana</h5>
                        </div>
                    @endforelse
                </div>

            @else
                {{-- PASSAGEIRO: PEDIDOS --}}
                <div class="row">
                    @forelse ($requests as $request)
                        @php $ride = $request->ride; @endphp

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="ride-card">

                                {{-- HEADER --}}
                                <div class="ride-card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $ride->driver->photo ? asset('storage/' . $ride->driver->photo) : asset('images/nophoto.png') }}"
                                             class="ride-avatar">
                                        <strong>{{ $ride->driver->name }}</strong>
                                    </div>
                                    <h6>Boleia #{{ $ride->id }}</h6>

                                    {{-- STATUS BOLEIA --}}
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

                                    {{-- ESTADO DO PEDIDO --}}
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>📬 Estado do pedido:</strong>
                                            <span class="badge ms-2
                                                @if ($request->status === 'pending') bg-warning text-dark
                                                @elseif ($request->status === 'accepted') bg-success
                                                @elseif ($request->status === 'rejected') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                @if ($request->status === 'pending') Pendente
                                                @elseif ($request->status === 'accepted') Aceite
                                                @elseif ($request->status === 'rejected') Recusado
                                                @else {{ ucfirst($request->status) }}
                                                @endif
                                            </span>
                                        </small>
                                    </div>

                                    {{-- {{-- TEAMS
                                    @if ($request->status === 'accepted' && $request->teams_link)
                                        <a href="{{ $request->teams_link }}" target="_blank"
                                           class="teams-btn mt-2">
                                            <i class="bi bi-microsoft-teams"> Teams</i>
                                        </a>
                                    @endif --}}
                                </div>

                                {{-- FOOTER --}}
                                <div class="ride-card-footer">
                                    <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">
                                        Ver boleia
                                    </a>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-car-front fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Ainda não pediste nenhuma boleia</h5>
                        </div>
                    @endforelse
                </div>
            @endif
        @endauth

        @guest
            <div class="row">
                <div class="col-12 text-center py-5">
                    <i class="bi bi-car-front fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Ainda não pediste nenhuma boleia</h5>
                </div>
            </div>
        @endguest

        <a href="{{ route('rides.all') }}" class="btn btn-secondary mt-4">← Voltar às boleias</a>

    </div>
@endsection
