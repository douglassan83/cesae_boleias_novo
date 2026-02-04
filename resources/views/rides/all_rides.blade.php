@extends('layouts.main_layout')
@section('content')
    @php use App\Models\RideRequest; @endphp

    <div class="container mt-4">
        {{-- TÍTULO DINÂMICO --}}
        <h3 class="mb-4">
            @if (auth()->user()->role == 'driver')
                Minhas Boleias (Motorista)
            @elseif(auth()->user()->role == 'passenger')
                Boleias perto de {{ auth()->user()->pickup_location ?? '(preencha perfil!)' }}
            @else
                TODAS as Boleias (Admin)
            @endif
        </h3>

        {{-- BOTÕES --}}
        @auth
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">Oferecer Boleia</a>

            @elseif (auth()->user()->role == 'driver')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">Oferecer Boleia</a>
            @elseif (auth()->user()->role == 'passenger')

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

        {{-- TABELA --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Motorista</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Lugares</th>
                        <th>Status Boleia</th>
                        <th>Ações</th>
                        <th>Status Pedido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rides as $ride)
                        <tr>
                            <td>{{ $ride->driver->name }}</td>
                            <td><strong>{{ $ride->pickup_location }}</strong></td>
                            <td>{{ $ride->destination_location }}</td>
                            <td>{{ date('d/m/Y', strtotime($ride->departure_date)) }}</td>
                            <td>{{ date('H:i', strtotime($ride->departure_time)) }}</td>
                            <td>
                                <span class="badge bg-primary fs-6">
                                    {{ $ride->available_seats }} / {{ $ride->total_seats }}
                                </span>
                            </td>

                            {{-- STATUS BOLEIA --}}
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
                            </td>

                            {{-- COLUNA AÇÕES --}}
                            <td>
                                @auth
                                    @if (auth()->user()->role === 'admin')
                                        {{-- ADMIN: sempre Ver Boleia --}}
                                        <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info">
                                            Ver Boleia
                                        </a>
                                    @elseif (auth()->user()->role === 'driver' && auth()->id() === $ride->driver_id)
                                        <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info">
                                            Ver Boleia
                                        </a>
                                    @elseif (auth()->user()->role === 'passenger')
                                        @php
                                            $meuPedido = $ride->rideRequests
                                                ->where('passenger_id', auth()->id())
                                                ->first();
                                        @endphp
                                        @if ($meuPedido)
                                            {{-- TEM PEDIDO: sempre mostra Ver Pedido --}}
                                            <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-warning">
                                                Ver Pedido
                                            </a>
                                        @elseif ($ride->status === 'active' && $ride->available_seats > 0)
                                            {{-- SEM PEDIDO + disponível --}}
                                            <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">
                                                Ver Boleia
                                            </a>
                                        @else
                                            {{-- SEM PEDIDO + lotada --}}
                                            <span class="badge bg-secondary">Indisponível</span>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login</a>
                                @endauth
                            </td>


                            {{-- COLUNA STATUS PEDIDO --}}
                            <td>
                                @auth
                                    @if (auth()->user()->role === 'driver' && auth()->id() === $ride->driver_id)
                                        {{-- Driver: contadores --}}
                                        @php
                                            $pending = $ride->rideRequests->where('status', 'pending')->count();
                                            $accepted = $ride->rideRequests->where('status', 'accepted')->count();
                                            $cancelled = $ride->rideRequests->where('status', 'cancelled')->count();
                                            $rejected = $ride->rideRequests->where('status', 'rejected')->count();
                                        @endphp
                                        @if ($pending > 0)
                                            <span class="badge bg-warning">{{ $pending }}
                                                recebido{{ $pending > 1 ? 's' : '' }}</span><br>
                                        @endif
                                        @if ($accepted > 0)
                                            <span class="badge bg-success">{{ $accepted }}
                                                aceito{{ $accepted > 1 ? 's' : '' }}</span><br>
                                        @endif
                                        @if ($cancelled > 0)
                                            <span class="badge bg-secondary">{{ $cancelled }}
                                                cancelado{{ $cancelled > 1 ? 's' : '' }}</span><br>
                                        @endif
                                        @if ($rejected > 0)
                                            <span class="badge bg-danger">{{ $rejected }}
                                                rejeitado{{ $rejected > 1 ? 's' : '' }}</span>
                                        @endif
                                        @if (!$pending && !$accepted && !$cancelled && !$rejected)
                                            <span class="badge bg-light text-muted">Nenhum pedido</span>
                                        @endif
                                    @elseif (auth()->user()->role === 'passenger')
                                        @php
                                            $meuPedido = $ride->rideRequests
                                                ->where('passenger_id', auth()->id())
                                                ->first();
                                        @endphp
                                        @if ($meuPedido)
                                            @switch($meuPedido->status)
                                                @case('pending')
                                                    <span class="badge bg-info">Pedido enviado</span>
                                                @break

                                                @case('accepted')
                                                    <span class="badge bg-success">Pedido aceito</span>
                                                @break

                                                @case('rejected')
                                                    <span class="badge bg-danger">Pedido recusado</span>
                                                @break

                                                @case('cancelled')
                                                    <span class="badge bg-secondary">Cancelado</span>
                                                @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-light text-muted">Sem pedido</span>
                                        @endif
                                    @endif
                                @endauth
                            </td>


                        </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Nenhuma boleia disponível</h5>
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
