@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    <h1 class="fw-bold mb-4">Mensagens Recebidas</h1>

    @if($messages->isEmpty())
        <div class="alert alert-info">
            Nenhuma mensagem foi enviada ainda.
        </div>
    @else

    <table class="table table-striped table-bordered">
    <thead class="table-dark">
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
        @foreach ($messages as $msg)
            <tr>
                <td>{{ $msg->id }}</td>
                <td>{{ $msg->name }}</td>
                <td>{{ $msg->email }}</td>
                <td>{{ ucfirst($msg->subject) }}</td>
                <td>{{ $msg->ride_reference ?? '-' }}</td>
                <td>{{ $msg->message }}</td>
                <td>{{ $msg->created_at->format('d/m/Y H:i') }}</td>

                {{-- ESTADO --}}
                <td>
                    @if ($msg->resolved)
                        <span class="badge bg-success">Resolvido</span>
                    @else
                        <span class="badge bg-warning text-dark">Pendente</span>
                    @endif
                </td>

                {{-- BOTÃO --}}
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
        @endforeach
    </tbody>
</table>


    @endif

</div>
@endsection
