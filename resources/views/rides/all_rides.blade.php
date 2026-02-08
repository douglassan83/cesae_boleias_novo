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

        {{-- BOTÕES --}}
        @auth
            @if (auth()->user()->role == 'admin')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">Adicionar Boleia</a>
                <a href="{{ route('rides.my_requests') }}" class="btn btn-primary mb-3">Pedidos Recebidos</a>
                <a href="{{ route('rides.my_requests') }}" class="btn btn-warning mb-3">Pedidos Solicitados</a>
                <a href="{{ route('admin.messages') }}" class="btn btn-danger mb-3">Ver Mensagens dos Utilizadores</a>

            @elseif (auth()->user()->role == 'driver')
                <a href="{{ route('rides.add') }}" class="btn btn-success mb-3">Adicionar Boleia</a>
                <a href="{{ route('rides.my_requests') }}" class="btn btn-primary mb-3">Pedidos Recebidos</a>

            @elseif (auth()->user()->role == 'passenger')
                <a href="{{ route('rides.my_requests') }}" class="btn btn-primary mb-3">Pedidos Solicitados</a>
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

        {{-- LISTA DE BOLEIAS --}}
        <div class="row">

            @forelse($rides as $ride)

                @php
                    // pedido do utilizador autenticado (qualquer estado)
                    $myRequest = $ride->rideRequests->where('passenger_id', auth()->id())->first();
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
                                📍 {{ $ride->pickup_location }} → {{ $ride->destination_location }}
                            </p>

                            <p class="mb-1">
                                📅 {{ date('d/m/Y', strtotime($ride->departure_date)) }}
                                ⏰ {{ date('H:i', strtotime($ride->departure_time)) }}
                            </p>

                            <span class="badge bg-primary">
                                👥 {{ $ride->available_seats }} / {{ $ride->total_seats }} lugares
                            </span>


                            {{-- PEDIDOS RECEBIDOS (SÓ MOTORISTA VÊ) --}}
                            @auth
                                @if (auth()->id() === $ride->driver_id)
                                    @php
                                        $pedidos = $ride->rideRequests()->latest()->get();
                                        $ultimos = []; // reset para esta boleia

                                        // Pega o ÚLTIMO pedido de CADA passageiro desta boleia
                                        foreach ($pedidos as $pedido) {
                                            $ultimos[$pedido->passenger_id] = $pedido->status;
                                        }

                                        // Conta os últimos status
                                        $pendentes = 0;
                                        $aceites = 0;
                                        $recusados = 0;

                                        foreach ($ultimos as $status) {
                                            if ($status == 'pending') {
                                                $pendentes++;
                                            }
                                            if ($status == 'accepted') {
                                                $aceites++;
                                            }
                                            if ($status == 'rejected') {
                                                $recusados++;
                                            }
                                        }
                                    @endphp

                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>📬 Pedidos:</strong>

                                            @if (count($ultimos) == 0)
                                                <span class="badge bg-secondary ms-2">Nenhum pedido</span>
                                            @else
                                                @if ($pendentes > 0)
                                                    <span class="badge bg-warning text-dark ms-1">{{ $pendentes }}
                                                        Pendente{{ $pendentes != 1 ? 's' : '' }}</span>
                                                @endif
                                                @if ($aceites > 0)
                                                    <span class="badge bg-success ms-1">{{ $aceites }}
                                                        Aceite{{ $aceites != 1 ? 's' : '' }}</span>
                                                @endif
                                                @if ($recusados > 0)
                                                    <span class="badge bg-danger ms-1">{{ $recusados }}
                                                        Recusado{{ $recusados != 1 ? 's' : '' }}</span>
                                                @endif
                                            @endif
                                        </small>
                                    </div>
                                @endif
                            @endauth





                            {{-- INFO DE PEDIDOS PARA PASSAGEIRO NESTE CARD --}}
                            @auth
                                @if (auth()->user()->role === 'passenger')
                                    @if ($myRequest)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small class="text-muted">
                                                <strong>📬 Pedidos:</strong>
                                                <span
                                                    class="badge ms-2
                        @if ($myRequest->status === 'pending') bg-warning text-dark
                        @elseif ($myRequest->status === 'accepted') bg-success
                        @elseif ($myRequest->status === 'rejected') bg-danger
                        @else bg-secondary @endif">
                                                    @if ($myRequest->status === 'pending')
                                                        Pedido enviado com sucesso
                                                    @elseif ($myRequest->status === 'accepted')
                                                        Aceite
                                                    @elseif ($myRequest->status === 'rejected')
                                                        Recusado
                                                    @else
                                                        {{ ucfirst($myRequest->status) }}
                                                    @endif
                                                </span>
                                            </small>
                                        </div>
                                    @else
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small class="text-muted">
                                                <strong>📬 Pedidos:</strong>
                                                <span class="badge bg-secondary ms-2">Nenhum pedido</span>
                                            </small>
                                        </div>
                                    @endif
                                @endif
                            @endauth



                        </div>

                        {{-- FOOTER --}}
                        <div class="ride-card-footer">

                            {{-- MOTORISTA --}}
                            @if (auth()->id() === $ride->driver_id)
                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-info">
                                    Ver boleia
                                </a>

                            {{-- PASSAGEIRO --}}
                            @elseif (auth()->user()->role === 'passenger')
                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">Ver boleia</a>

                            {{-- ADMIN --}}
                            @elseif (auth()->user()->role === 'admin')
                                <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-dark">Ver detalhes</a>
                            @endif

                            {{-- TEAMS --}}
                            @if ($myRequest && $myRequest->teams_link)
                                <a href="{{ $myRequest->teams_link }}" target="_blank"
                                   class="teams-btn mt-2" title="Abrir reunião no Microsoft Teams">
                                    <i class="bi bi-microsoft-teams"></i> Teams
                                @if ($myRequest)
                                    {{-- já existe pedido para esta boleia --}}
                                    <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-warning">
                                        Ver pedido
                                    </a>
                                @else
                                    @if ($ride->status === 'active' && $ride->available_seats > 0)
                                        <a href="{{ route('rides.view', $ride->id) }}" class="btn btn-sm btn-primary">
                                            Ver boleia
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            Indisponível
                                        </button>
                                    @endif
                                @endif
                            @endif

                            {{-- botão Teams (quando passageiro tem pedido com link) --}}
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
                    <h5 class="mt-3 text-muted">Nenhuma boleia disponível</h5>

                    @if (auth()->user()->role != 'driver' && auth()->user()->pickup_location == '')
                        <div class="alert alert-danger mt-3">
                            <i>Atenção: preencha o ponto de partida no perfil!</i>

                    @if (Auth::user()->role != 'driver')
                        <h5 class="mt-3 text-muted">
                            Nenhuma boleia disponível
                        </h5>
                    @endif

                    @if (Auth::user()->role == 'driver')
                        <h5 class="mt-3 text-muted">
                            Nenhuma boleia oferecida
                        </h5>
                    @endif

                    @if (Auth::user()->role != 'driver' && auth()->user()->pickup_location == '')
                        <div class="alert alert-danger">
                            <h5>
                                <i>
                                    Atenção: preencha ponto de partida no perfil!
                                </i>
                            </h5>
                        </div>
                    @elseif (Auth::user()->role == 'driver' && auth()->user()->pickup_location == '' && auth()->user()->photo == '')
                        <h5>
                            <i>
                                Atenção: complete os seus dados no perfil!
                            </i>
                        </h5>
                    @endif
                </div>
            @endforelse

        </div>

        </div> {{-- row --}}
    </div> {{-- container --}}
@endsection
