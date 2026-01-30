@extends('layouts.main_layout')

@section('content')
    <div class="container">
        <br>
        <h3>{{ $pageTitle }}</h3>
        <br>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>

                    <th>Boleia</th>
                    <th>Motorista</th>
                    <th>Passageiro</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Data/Hora</th>
                    <th>Status pedido</th>
                    <th>Pedido</th>

                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>

                        <td>{{ $request->ride->driver->name ?? 'N/A' }}</td>
                        <td>{{ $request->passenger->name ?? 'N/A' }}</td>
                        <td>{{ $request->ride->pickup_location ?? 'N/A' }}</td>
                        <td>{{ $request->ride->destination_location ?? 'N/A' }}</td>

                        {{-- DATA / HORA --}}
                        <td>
                            {{ optional($request->ride->departure_date)->format('d/m/Y') ?? 'N/A' }}
                            -
                            {{ optional($request->ride->departure_time)->format('H:i') ?? 'N/A' }}
                        </td>

                        {{-- STATUS --}}
                        <td>
                            <span
                                class="badge
            @if ($request->status === 'pending') bg-warning
            @elseif ($request->status === 'accepted') bg-success
            @else bg-danger @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>

                        {{-- PEDIDO --}}
                        <td>
                            <a href="{{ route('rides.view', $request->ride->id) }}" class="btn btn-primary btn-sm mb-1">
                                VER
                            </a>

                            @if ($request->status === 'accepted' && $request->teams_link)
                                <br>
                                <a href="{{ $request->teams_link }}" target="_blank" class="btn btn-sm btn-success mt-1">
                                    🎥 Entrar no Teams
                                </a>
                            @endif

                            {{-- AÇÕES DO MOTORISTA --}}
                            @if (auth()->user()->role === 'driver' && $request->status === 'pending' && $request->ride->driver_id === auth()->id())
                                <div class="d-flex gap-1 mt-1">
                                    <form method="POST" action="{{ route('ride_requests.accept', $request->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Aceitar</button>
                                    </form>

                                    <form method="POST" action="{{ route('ride_requests.reject', $request->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Rejeitar</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Nenhum pedido de boleia encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('rides.all') }}" class="btn btn-secondary">← Voltar às boleias</a>
    </div>
@endsection
