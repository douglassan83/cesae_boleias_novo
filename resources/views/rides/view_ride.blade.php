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
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- CARD PRINCIPAL DA BOLEIA --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Motorista: {{ $ride->driver->name ?? 'N/A' }}</h5>

                <p><strong>Origem:</strong> {{ $ride->pickup_location ?? 'N/A' }}</p>
                <p><strong>Destino:</strong> {{ $ride->destination_location ?? 'N/A' }}</p>

                <p><strong>Data:</strong> {{ $ride->departure_date ? $ride->departure_date->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>Hora:</strong> {{ $ride->departure_time ? $ride->departure_time->format('H:i') : 'N/A' }}</p>

                <p><strong>Lugares:</strong> {{ $ride->available_seats ?? 0 }} / {{ $ride->total_seats ?? 0 }}</p>

                @if ($ride->observations)
                    <p><strong>Observações:</strong> {{ $ride->observations }}</p>
                @endif

                <p><strong>Status:</strong>
                    <span class="badge {{ $ride->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $ride->status === 'active' ? 'Ativa' : ucfirst($ride->status) }}
                    </span>
                </p>

                {{-- PEDIR BOLEIA / CANCELAR PEDIDO --}}
                @auth
                    @if (auth()->id() != $ride->driver_id)
                        @if (!isset($pedido))
                            @if (($ride->available_seats ?? 0) > 0 && $ride->status == 'active')
                                <div class="mt-3">
                                    <form method="POST" action="{{ route('rides.request') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="ride_id" value="{{ $ride->id }}">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-car"></i> Pedir Boleia
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <hr>
                            <form method="POST" action="{{ route('ride_requests.cancel', $pedido->id) }}" class="d-inline"
                                  onsubmit="return confirm('Tem a certeza que deseja cancelar o pedido?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-lg">
                                    ❌ Cancelar Pedido
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth
            </div>
        </div>

        {{-- 🆕 SUBCARD: PASSAGEIROS VINCULADOS (SÓ MOTORISTA VÊ) --}}
        @auth
            @if (auth()->id() === $ride->driver_id)
                @php
                    $requests = $ride->rideRequests()->with('passenger:id,name,email')->get();
                @endphp

                @if ($requests->count() > 0)
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-users"></i> Passageiros ({{ $requests->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($requests as $request)
                                <div class="row mb-3 p-3 border-bottom">
                                    <div class="col-md-6">
                                        <h6>{{ $request->passenger->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $request->passenger->email ?? 'N/A' }}</small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        {{-- STATUS --}}
                                        <span class="badge me-2
                                            @if ($request->status === 'pending') bg-warning
                                            @elseif ($request->status === 'accepted') bg-success
                                            @else bg-danger @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>

                                        {{-- BOTÕES MOTORISTA (SÓ PENDING) --}}
                                        @if ($request->status === 'pending')
                                            <div class="btn-group btn-group-sm" role="group">
                                                <form method="POST" action="{{ route('ride_requests.accept', $request->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check"></i> Aceitar
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('ride_requests.reject', $request->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Rejeitar {{ $request->passenger->name ?? '' }}?')">
                                                        <i class="fas fa-times"></i> Rejeitar
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        {{-- TEAMS (SÓ ACEITO) --}}
                                        @if ($request->status === 'accepted' && $request->teams_link)
                                            <a href="{{ $request->teams_link }}" target="_blank"
                                               class="btn btn-success btn-sm mt-1">
                                                <i class="fab fa-microsoft"></i> Teams
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Ainda não há pedidos para esta boleia.
                    </div>
                @endif
            @endif
        @endauth

        {{-- INFO PASSAGEIRO (ACEITO) --}}
        @if (auth()->check() && isset($pedido) && $pedido->status === 'accepted')
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">✔ Pedido Aceito!</h5>
                </div>
                <div class="card-body">
                    <p><strong>Motorista:</strong> {{ $ride->driver->name }}</p>
                    <p><strong>Email:</strong> {{ $ride->driver->email }}</p>
                    <p><strong>Telefone:</strong> {{ $ride->driver->phone ?? 'Não disponível' }}</p>
                    @if ($pedido->teams_link)
                        <a href="{{ $pedido->teams_link }}" target="_blank" class="btn btn-success">
                            🎥 Entrar no Teams
                        </a>
                    @endif
                </div>
            </div>
        @endif

        {{-- BOTÕES MOTORISTA --}}
        <div class="mt-3">
            @auth
                @if (auth()->id() === $ride->driver_id)
                    <a href="{{ route('rides.edit', $ride->id) }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('rides.delete', $ride->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Excluir esta boleia?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">🗑 Excluir</button>
                    </form>
                @endif
            @endauth
            <a href="{{ route('rides.all') }}" class="btn btn-secondary ms-2">← Voltar</a>
        </div>
    </div>

@endsection
