@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">Contactar Suporte</h2>

    <form action="{{ route('support.send') }}" method="POST">
        @csrf

        <input type="hidden" name="ride_id" value="{{ $ride_id }}">
        <input type="hidden" name="request_id" value="{{ $request_id }}">
        <input type="hidden" name="subject" value="pedido_rejeitado">



        <div class="mb-3">
            <label class="form-label">Mensagem</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>

        <button class="btn btn-primary">Enviar</button>
    </form>

</div>
@endsection
