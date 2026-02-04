@extends('layouts.main_layout')

@section('content')
    <div class="container page-section">
        <br>
        <h3>Boleia #{{ $ride->id }}</h3>

        @auth
            @php
                $pedido = \App\Models\RideRequest::where('ride_id', $ride->id)
                    ->where('passenger_id', auth()->id())
                    ->whereIn('status', ['pending', 'accepted'])
                    ->first();

                $rejeitado = \App\Models\RideRequest::where('ride_id', $ride->id)
                    ->where('passenger_id', auth()->id())
                    ->where('status', 'rejected')
                    ->first();
            @endphp
        @endauth

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- CARD PRINCIPAL DA BOLEIA --}}
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <div class="ride-card">

                    {{-- HEADER --}}
                    <div class="ride-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $ride->driver->photo ? asset('storage/' . $ride->driver->photo) : asset('images/nophoto.png') }}"
                                class="ride-avatar">

                            <strong>{{ $ride->driver->name }}</strong>
                        </div>

                        <span
                            class="badge
                    {{ $ride->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($ride->status) }}
                        </span>
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

                        @if ($ride->observations)
                            <p class="mt-2">
                                📝 {{ $ride->observations }}
                            </p>
                        @endif

                        {{-- TEAMS (SÓ SE ACEITE) --}}

                    </div>

                    {{-- FOOTER --}}
                    <div class="ride-card-footer">

                        {{-- PASSAGEIRO --}}
                        @auth
                            @if (auth()->id() !== $ride->driver_id)
                                @if ($rejeitado)
                                    {{-- MOSTRAR QUE FOI REJEITADO --}}
                                    <button class="btn btn-danger btn-sm" disabled title="O motorista recusou o teu pedido">
                                        ❌ Pedido recusado
                                    </button>
                                @elseif (!$pedido && $ride->status === 'active' && $ride->available_seats > 0)
                                    <form method="POST" action="{{ route('rides.request') }}">
                                        @csrf
                                        {{-- Enviar ID da boleia para o controlador --}}
                                        <input type="hidden" name="ride_id" value="{{ $ride->id }}">
                                        <button class="btn btn-primary btn-sm">
                                            🚗 Pedir boleia
                                        </button>
                                    </form>
                                @elseif ($pedido)
                                    <form method="POST" action="{{ route('ride_requests.cancel', $pedido->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            ❌ Cancelar pedido
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endauth

                        {{-- MOTORISTA --}}
                        @auth
                            @if (auth()->id() === $ride->driver_id)
                                <a href="{{ route('rides.edit', $ride->id) }}" class="btn btn-warning btn-sm">
                                    ✏️ Editar
                                </a>
                            @endif
                        @endauth

                        <a href="{{ route('rides.all') }}" class="btn btn-secondary btn-sm">
                            ← Voltar
                        </a>

                    </div>

                </div>
            </div>
        </div>


        {{-- 🆕 SUBCARD: PASSAGEIROS VINCULADOS (SÓ MOTORISTA VÊ) --}}
        @auth
            @if (auth()->id() === $ride->driver_id)
                @php
                    $requests = $ride->rideRequests()->with('passenger:id,name,email')->get();
                @endphp

                <div class="row justify-content-center mt-4">
                    <div class="col-md-6 col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                Pedidos recebidos
                            </div>
                            <div class="card-body">
                                @forelse ($requests as $request)
                                    <div class="row mb-3 p-3 border-bottom">
                                        <div class="col-md-6">
                                            <h6>{{ $request->passenger->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $request->passenger->email ?? 'N/A' }}</small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {{-- STATUS --}}
                                            <span
                                                class="badge me-2
                                        @if ($request->status === 'pending') bg-warning
                                        @elseif ($request->status === 'accepted') bg-success
                                        @else bg-danger @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>

                                            {{-- BOTÕES MOTORISTA (SÓ PENDING) --}}
                                            @if ($request->status === 'pending')
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <form method="POST"
                                                        action="{{ route('ride_requests.accept', $request->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check"></i> Aceitar
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('ride_requests.reject', $request->id) }}"
                                                        class="d-inline">
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
                                @empty
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i> Ainda não há pedidos para esta boleia.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        {{-- INFO PASSAGEIRO (ACEITO) --}}
        @if (auth()->check() && isset($pedido) && $pedido->status === 'accepted')
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-lg-5">

                    <div class="ride-card accepted-card">

                        {{-- HEADER --}}
                        <div class="ride-card-header bg-success text-white">
                            <strong>✔ Pedido Aceito</strong>
                        </div>

                        {{-- BODY --}}
                        <div class="ride-card-body">

                            <p class="mb-1">
                                👤 <strong>Motorista:</strong> {{ $ride->driver->name }}
                            </p>

                            <p class="mb-1">
                                📧 <strong>Email:</strong> {{ $ride->driver->email }}
                            </p>

                            <p class="mb-2">
                                📞 <strong>Telefone:</strong>
                                {{ $ride->driver->phone ?? 'Não disponível' }}
                            </p>

                            @if ($pedido->teams_link)
                                <a href="{{ $pedido->teams_link }}" target="_blank" class="teams-btn mt-2">
                                    🎥 Entrar no Teams
                                </a>
                            @endif

                        </div>

                    </div>

                </div>
            </div>
        @endif

        {{-- INFO PASSAGEIRO (REJEITADO) --}}
        @if (auth()->check() && isset($rejeitado) && $rejeitado->status === 'rejected')
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-lg-5">

                    <div class="card border-danger">

                        {{-- HEADER --}}
                        <div class="card-header bg-danger text-white">
                            <strong>❌ Pedido Recusado</strong>
                        </div>

                        {{-- BODY --}}
                        <div class="card-body">

                            <p class="mb-2">
                                Desculpe, o motorista recusou o teu pedido para esta boleia.
                            </p>

                            <p class="mb-3 text-muted">
                                <i class="fas fa-info-circle"></i> Não é possível pedir novamente para esta boleia.
                            </p>

                            <p class="mb-2">
                                <strong>Próximas ações:</strong>
                            </p>
                            <ul class="mb-0">
                                <li>Procura outras boleias disponíveis</li>
                                <li>Contacta o motorista se achares que houve um erro</li>
                                <li>Verifica o teu perfil completo para melhores resultados</li>
                            </ul>

                        </div>

                    </div>

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
 