@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">Mensagens Recebidas</h2>

    <table class="table table-striped table-bordered table-hover">
    <thead class="table">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Assunto</th>
            <th>Referência</th>
            <th>Mensagem</th>
            <th>Data</th>
            <th>Estado</th>
            <th>Ação</th>
        </tr>
    </thead>

    <tbody>
       {{--  @foreach ($messages as $msg)
        <tr>
            <td>{{ $msg->id }}</td>
            <td>{{ $msg->name }}</td>
            <td>{{ $msg->email }}</td>
            <td>{{ ucfirst($msg->subject) }}</td>
            <td>{{ $msg->ride_reference ?? '-' }}</td>
            <td>{{ $msg->message }}</td>
            <td>{{ $msg->created_at->format('d/m/Y H:i') }}</td>

            // ESTADO
            <td>
                @if ($msg->resolved)
                    <span class="badge bg-success">Resolvido</span>
                @else
                    <span class="badge bg-warning text-dark">Pendente</span>
                @endif
            </td>

            // BOTÃO
            <td>
                @if (!$msg->resolved)
                    <form action="{{ route('admin.messages.resolve', $msg->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-success">
                            Marcar como Resolvido
                        </button>
                    </form>
                @else
                    <span class="text-muted">✔</span>
                @endif
            </td>
        </tr>
    @endforeach --}}

        <!-- DADOS SIMULADOS PARA APRESENTAÇÃO -->
        <tr>
            <td>1</td>
            <td>João Silva</td>
            <td>joao@cesae.pt</td>
            <td>Problema boleia</td>
            <td>Boleia #45</td>
            <td>Não consigo cancelar meu pedido...</td>
            <td>10/02/26 14:30</td>
            <td><span class="badge bg-warning text-dark">Pendente</span></td>
            <td>
                <form action="#" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success" type="button">
                        Marcar como Resolvido
                    </button>
                </form>
            </td>
        </tr>
        <tr class="table-success">
            <td>2</td>
            <td>Maria Santos</td>
            <td>maria@cesae.pt</td>
            <td>Boleia aceita</td>
            <td>-</td>
            <td>Obrigada! Tudo resolvido ✅</td>
            <td>09/02/26 16:15</td>
            <td><span class="badge bg-success">Resolvido</span></td>
            <td><span class="text-muted fs-5">✔</span></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Pedro Costa</td>
            <td>pedro@cesae.pt</td>
            <td>Link Teams</td>
            <td>Boleia #52</td>
            <td>O link do Teams não funciona...</td>
            <td>10/02/26 09:20</td>
            <td><span class="badge bg-warning text-dark">Pendente</span></td>
            <td>
                <form action="#" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success" type="button">
                        Marcar como Resolvido
                    </button>
                </form>
            </td>
        </tr>
    </tbody>
</table>

</div>
@endsection
