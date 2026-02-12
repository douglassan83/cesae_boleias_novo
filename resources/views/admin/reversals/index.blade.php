@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    <h1 class="fw-bold mb-4">Pedidos de Reversão de Boleias</h1>

    @if($reversals->isEmpty())
        <div class="alert alert-info">
            Nenhum pedido de reversão pendente.
        </div>
    @else

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Passageiro</th>
                <th>Email</th>
                <th>ID Pedido Original</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reversals as $rev)
                <tr>
                    <td>{{ $rev->id }}</td>

                    {{-- Nome do passageiro --}}
                    <td>{{ $rev->passenger->name }}</td>

                    {{-- Email do passageiro --}}
                    <td>{{ $rev->passenger->email }}</td>

                    {{-- ID do pedido rejeitado --}}
                    <td>#{{ $rev->ride_request_id }}</td>

                    {{-- Estado --}}
                    <td>
                        @if ($rev->status === 'pending')
                            <span class="badge bg-warning text-dark">Pendente</span>
                        @elseif ($rev->status === 'approved')
                            <span class="badge bg-success">Aprovado</span>
                        @else
                            <span class="badge bg-danger">Rejeitado</span>
                        @endif
                    </td>

                    {{-- Data --}}
                    <td>{{ $rev->created_at->format('d/m/Y H:i') }}</td>

                    {{-- Botões --}}
                    <td>
                        @if ($rev->status === 'pending')
                            <div class="d-flex gap-2">

                                {{-- Aprovar --}}
                                <form action="{{ route('ride.reversal.approve', $rev->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Aprovar</button>
                                </form>

                                {{-- Rejeitar --}}
                                <form action="{{ route('ride.reversal.reject', $rev->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Rejeitar</button>
                                </form>

                            </div>
                        @else
                            <span class="text-muted">✔ Sem ações</span>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    @endif

</div>
@endsection
