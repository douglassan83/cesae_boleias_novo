@extends('layouts.main_layout')
@section('content')
    @php use App\Models\RideRequest; @endphp

    <div class="container mt-4">
        {{-- TÍTULO DINÂMICO POR ROLE --}}
        <h3 class="mb-4">
            @if (auth()->user()->role == 'driver')
                Minhas Boleias oferecidas (Motorista)
            @elseif(auth()->user()->role == 'passenger')
                Procurar Boleias (Passageiro) ({{ auth()->user()->pickup_location ?? 'Preencha perfil!' }})
            @else
                TODAS Boleias (Admin)
            @endif
        </h3>

        {{-- BOTões --}}

        @auth
            {{-- ADMIN: vê 3 botões --}}
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">
                    Adicionar Boleia
                </a>
                <a href="{{ route('ride_requests.my') }}" class="btn btn-primary mb-3">
                    Pedidos Recebidos
                </a>
                <a href="{{ route('ride_requests.my') }}" class="btn btn-warning mb-3">
                    Pedidos Solicitados
                </a>

                {{-- DRIVER: 2 botões --}}
            @elseif (auth()->user()->role == 'driver')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">
                    Adicionar Boleia
                </a>
                <a href="{{ route('ride_requests.my') }}" class="btn btn-primary mb-3">
                    Pedidos Recebidos
                </a>

                {{-- PASSENGER: 1 botão --}}
            @elseif (auth()->user()->role == 'passenger')
                <a href="{{ route('ride_requests.my') }}" class="btn btn-primary mb-3">
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
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>

                        <th>Motorista</th>
                        <th>Localização de Partida</th>
                        <th>Localização de Destino</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Total de Lugares</th>
                        <th>Status</th>
                        <th>Ações(buttons)</th>
                        <th>Link Teams</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rides as $ride)
                        <tr> {{-- DB INGLÊS --}}

                            <td>{{ $ride->driver->name }}</td>
                            <td><strong>{{ $ride->pickup_location }}</strong></td>
                            <td> {{ $ride->destination_location }} </td>
                            <td>{{ date('d/m/Y', strtotime($ride->departure_date)) }}</td>
                            <td>{{ date('H:i', strtotime($ride->departure_time)) }}</td>
                            <td>
                                <span class="badge bg-info fs-6">
                                    {{ $ride->available_seats }} / {{ $ride->total_seats }}
                                </span>
                            </td>

                            {{-- STATUS LABELS PORTUGUÊS --}}
                            <td>
                                @switch($ride->status)
                                    @case('active')
                                        <span class="badge bg-success">🟢 Ativa</span>
                                    @break

                                    @case('full')
                                        <span class="badge bg-secondary">🔴 Lotada</span>
                                    @break

                                    @default
                                        <span class="badge bg-danger">❌ {{ ucfirst($ride->status) }}</span>
                                @endswitch
                            <td>
                                @auth
                                    {{-- 1️⃣ MOTORISTA DONO DA BOLEIA --}}
                                    @if (auth()->id() === $ride->driver_id)
                                        <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info me-1">
                                            Ver
                                        </a>

                                        {{-- contador de pedidos pendentes --}}
                                        @php
                                            $pendingCount = \App\Models\RideRequest::where('ride_id', $ride->id)
                                                ->where('status', 'pending')
                                                ->count();
                                        @endphp

                                        @if ($pendingCount > 0)
                                            <span class="badge bg-warning text-dark">
                                                📩 {{ $pendingCount }} pedido{{ $pendingCount > 1 ? 's' : '' }}
                                            </span>
                                        @endif

                                        {{-- 2️⃣ PASSAGEIRO --}}
                                    @elseif (auth()->user()->role === 'passenger')
                                        @php
                                            $alreadyRequested = \App\Models\RideRequest::where('ride_id', $ride->id)
                                                ->where('passenger_id', auth()->id())
                                                ->exists();
                                        @endphp

                                        @if ($ride->status === 'active' && $ride->available_seats > 0)
                                            @if ($alreadyRequested)
                                                <span class="badge bg-warning text-dark">
                                                    Pedido enviado com sucesso
                                                </span>
                                            @else
                                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">
                                                   Ver boleia
                                                </a>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Indisponível</span>
                                        @endif
                                    @endif
                                @else
                                    {{-- 3️⃣ NÃO AUTENTICADO --}}
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                                        Faça login para pedir
                                    </a>
                                @endauth

                            </td>

                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">
                                        Nenhuma boleia disponível no teu filtro
                                    </h5>
                                    @if (auth()->user()->role == 'passenger' && !auth()->user()->pickup_location)
                                        <p>👆 Preencha <strong>pickup_location</strong> no perfil!</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
