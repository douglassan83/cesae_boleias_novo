@extends('layouts.main_layout')

@section('content')
    <div class="container page-section">

        @auth
            @php
                // CARREGA MOTORISTA + PASSAGEIROS
                $ride->load('driver:id,name,photo,email,whatsapp_phone');
                $ride->load('rideRequests.passenger:id,name,photo,email');

                $pedido = \App\Models\RideRequest::where('ride_id', $ride->id)
                    ->where('passenger_id', auth()->id())
                    ->whereIn('status', ['pending', 'accepted'])
                    ->first();

                $rejeitado = \App\Models\RideRequest::where('ride_id', $ride->id)
                    ->where('passenger_id', auth()->id())
                    ->where('status', 'rejected')
                    ->first();

                // CORREÇÃO: define $requests para subcard
                $requests = $ride->rideRequests()->with('passenger:id,name,photo,email')->get();
            @endphp
        @endauth

        {{-- ALERTAS --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- CARD PRINCIPAL --}}
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
                        <h6>Boleia #{{ $ride->id }}</h6>
                        <span class="badge {{ $ride->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($ride->status) }}
                        </span>
                    </div>

                    {{-- BODY --}}
                    <div class="ride-card-body">
                        <p class="fw-semibold mb-2">
                            <small>Origem:</small> {{ $ride->pickup_location }}
                        </p>
                        <p class="fw-semibold mb-2">
                            <small>Destino:</small> {{ $ride->destination_location }}
                        </p>
                        <p class="mb-1">
                            📅 {{ optional($ride->departure_date)->format('d/m/Y') }}
                            ⏰ {{ optional($ride->departure_time)->format('H:i') }}
                        </p>
                        <span class="badge bg-primary">
                            👥 {{ $ride->available_seats }} / {{ $ride->total_seats }} lugares
                        </span>
                        @if ($ride->observations)
                            <p class="mt-2">📝 {{ $ride->observations }}</p>
                        @endif
                    </div>

                    {{-- FOOTER --}}
                    <div class="ride-card-footer">
                        {{-- PASSAGEIRO --}}
                        @auth
                            @if (auth()->id() !== $ride->driver_id)
                                @if ($rejeitado)
                                    <button class="btn btn-danger btn-sm" disabled>❌ Pedido recusado</button>
                                @elseif (!$pedido && $ride->status === 'active' && $ride->available_seats > 0)
                                    <form method="POST" action="{{ route('rides.request') }}">
                                        @csrf
                                        <input type="hidden" name="ride_id" value="{{ $ride->id }}">
                                        <button class="btn btn-primary btn-sm">🚗 Pedir boleia</button>
                                    </form>
                                @elseif ($pedido)
                                    <form method="POST" action="{{ route('ride_requests.cancel', $pedido->id) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">❌ Cancelar pedido</button>
                                    </form>
                                @endif
                            @endif
                        @endauth

                        <a href="{{ route('rides.all') }}" class="btn btn-secondary btn-sm">← Voltar</a>
                        @auth
                            @if (auth()->id() === $ride->driver_id)
                                <a href="{{ route('rides.edit', $ride->id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                                <form action="{{ route('rides.delete', $ride->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Excluir esta boleia?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑 Excluir</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- SUBCARD PASSAGEIROS (SÓ MOTORISTA) --}}
@auth
    @if (auth()->id() === $ride->driver_id)
        @php $requests = $ride->rideRequests()->with('passenger:id,name,photo,email')->get(); @endphp

        <div class="row justify-content-center mt-4">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">Pedidos recebidos</div>
                    <div class="card-body">
                        @forelse ($requests as $request)
                          
                            <div class="row mb-3 p-2 border-bottom">
                                {{-- PASSAGEIRO --}}
                                <div class="col-md-5">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <img src="{{ $request->passenger->photo ? asset('storage/' . $request->passenger->photo) : asset('images/nophoto.png') }}"
                                             class="rounded-circle" style="width: 45px; height: 45px;">
                                        <h6 class="mb-1">{{ $request->passenger->name ?? 'N/A' }}</h6>
                                    </div>
                                    {{-- EMAIL TOTAL LARGURA --}}
                                    <div style="width: 100%;">
                                        <small class="text-muted d-block">{{ $request->passenger->email ?? 'N/A' }}</small>
                                    </div>
                                </div>

                                {{-- STATUS --}}
                                <div class="col-md-2 text-center">
                                    <span class="badge fs-8
                                        @if ($request->status === 'pending') bg-warning
                                        @elseif ($request->status === 'accepted') bg-success
                                        @else bg-danger @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>

                                {{-- BOTÕES --}}
                                <div class="col-md-5 text-end">
                                    @if ($request->status === 'pending')
                                        <div class="btn-group btn-group-sm gap-2">
                                            <form method="POST" action="{{ route('ride_requests.accept', $request->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Aceitar</button>
                                            </form>
                                            <form method="POST" action="{{ route('ride_requests.reject', $request->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Rejeitar {{ $request->passenger->name ?? '' }}?')">
                                                    Rejeitar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    @if ($request->status === 'accepted' && $request->teams_link)
                                        <a href="{{ $request->teams_link }}" target="_blank" class="teams-btn mt-2 d-block">
                                            <i class="bi bi-microsoft-teams"> Teams</i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse ($requests as $request)
                                    <div class="row mb-3 p-2 border-bottom">
                                        {{-- PASSAGEIRO (ESQUERDA) --}}
                                        <div class="col-md-5">
                                            <h6>{{ $request->passenger->name ?? 'N/A' }}</h6>
                                            <p id="card-email-passenger" class="text-muted">
                                                {{ $request->passenger->email ?? 'N/A' }}
                                            </p>
                                        </div>

                                        {{-- STATUS CENTRALIZADO --}}
                                        <div class="col-md-2 text-center">
                                            <span
                                                class="badge fs-8
                    @if ($request->status === 'pending') bg-warning
                    @elseif ($request->status === 'accepted') bg-success
                    @else bg-danger @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>

                                        {{-- BOTÕES (DIREITA) --}}
                                        <div class="col-md-5 text-end">
                                            {{-- STATUS (já foi mostrado acima, removido daqui) --}}
                                            <div>
                                                {{-- BOTÕES MOTORISTA (SÓ PENDING) --}}
                                                @if ($request->status === 'pending')
                                                    <div class="btn-group btn-group-sm gap-2" role="group">
                                                        <form method="POST"
                                                            action="{{ route('ride_requests.accept', $request->id) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check"></i> Aceitar
                                                            </button>
                                                        </form>
                                                        <form method="POST"
                                                            action="{{ route('ride_requests.reject', $request->id) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Rejeitar {{ $request->passenger->name ?? '' }}?')">
                                                                <i class="fas fa-times"></i> Rejeitar
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif

                                                {{-- TEAMS (SÓ ACEITO) --}}
                                                @if ($request->status === 'accepted' && $request->teams_link)
                                                    <a href="{{ $request->teams_link }}" target="_blank"
                                                        class="teams-btn mt-2">
                                                        <i class="bi bi-microsoft-teams"> Teams</i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <p>Nenhum pedido de boleia recebido.</p>
                                    </div>
                                @endforelse
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <p>Nenhum pedido de boleia recebido.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth


        {{-- INFO DO PEDIDO ACEITE --}}
@php
    $user = auth()->user();

    // MOTORISTA e ADMIN → podem ver qualquer passageiro aceite
    if ($user->role === 'driver' || $user->role === 'admin') {
        $pedidoAceito = $ride->rideRequests->where('status', 'accepted')->first();
    }

    // PASSAGEIRO → só vê o próprio pedido aceite
    if ($user->role === 'passenger') {
        $pedidoAceito = $ride->rideRequests
            ->where('passenger_id', $user->id)
            ->where('status', 'accepted')
            ->first();
    }
@endphp

@if ($pedidoAceito)
    <div class="row justify-content-center mt-4">
        <div class="col-md-6 col-lg-5">

            <div class="ride-card accepted-card">

                <div class="ride-card-header bg-success text-white">
                    <strong>✔ Pedido Aceite</strong>
                </div>

                <div class="ride-card-body">

                    {{-- MOTORISTA E ADMIN VEEM O PASSAGEIRO --}}
                    @if ($user->role !== 'passenger')
                        <p class="mb-1">
                            👤 <strong>Passageiro:</strong> {{ $pedidoAceito->passenger->name }}
                        </p>
                        <p class="mb-1">
                            📧 {{ $pedidoAceito->passenger->email }}
                        </p>
                        <p class="mb-1">
                            📞 {{ $pedidoAceito->passenger->phone ?? 'Não disponível' }}
                        </p>
                    @endif

                    {{-- PASSAGEIRO VÊ APENAS O MOTORISTA --}}
                    @if ($user->role === 'passenger')
                        <p class="mb-1">
                            👤 <strong>Motorista:</strong> {{ $ride->driver->name }}
                        </p>
                        <p class="mb-1">
                            📧 {{ $ride->driver->email }}
                        </p>
                        <p class="mb-1">
                            📞 {{ $ride->driver->phone ?? 'Não disponível' }}
                        </p>
                    @endif

                    @if ($pedidoAceito->teams_link)
                        <a href="{{ $pedidoAceito->teams_link }}" target="_blank" class="teams-btn mt-2">
                            <i class="bi bi-microsoft-teams"></i> Teams
                        </a>
                    @endif

        {{-- INFO PASSAGEIRO ACEITO --}}
        @if (auth()->check() && isset($pedido) && $pedido->status === 'accepted')
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-lg-5">
                    <div class="ride-card accepted-card">
                        <div class="ride-card-header bg-success text-white">
                            <strong>✔ Pedido Aceito</strong>
                        </div>
                        <div class="ride-card-body">
                            <p class="mb-1">👤 <strong>Motorista:</strong> {{ $ride->driver->name }}</p>
                            <p class="mb-1">📧 <small>Email:</small> {{ $ride->driver->email }}</p>
                            @if ($pedido->teams_link)
                                <a href="{{ $pedido->teams_link }}" target="_blank" class="teams-btn mt-2">
                                    <i class="bi bi-microsoft-teams"> Teams</i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endif



        {{-- INFO PASSAGEIRO REJEITADO --}}
        @if (auth()->check() && isset($rejeitado) && $rejeitado->status === 'rejected')
            <div class="row justify-content-center mt-4">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <strong>❌ Pedido Recusado</strong>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">Desculpe, o motorista recusou o teu pedido.</p>
                            <p class="mb-3 text-muted">
                                <i class="fas fa-info-circle"></i> Não é possível pedir novamente para esta boleia.
                            </p>
                            

                            <p class="mb-2">
                                <strong>Próximas ações:</strong>
                            </p>
                            <p class="mb-2"><strong>Próximas ações:</strong></p>
                            <ul class="mb-0">
                                <li>Procura outras boleias disponíveis</li>
                                <li>Contacta o suporte na área de contactos caso haja algum erro.</li>
                                <li>Verifica o teu perfil completo para melhores resultados</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
