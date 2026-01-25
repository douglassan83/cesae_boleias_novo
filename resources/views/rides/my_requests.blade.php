@extends('layouts.main_layout')
@section('content')
<br>
<h3>Meus Pedidos</h3>
<br>
@foreach($requests as $request)
<div class="card mb-2">
    <div class="card-body">
        <strong>Boleia:</strong> {{ $request->ride->origem_cidade }} → {{ $request->ride->destino_cidade }}<br>
        <strong>Status:</strong> <span class="badge {{ $request->status == 'pendente' ? 'bg-warning' : 'bg-success' }}">{{ $request->status }}</span>
        @if($request->mensagem) <strong>Mensagem:</strong> {{ $request->mensagem }} @endif
    </div>
</div>
@endforeach
@endsection
