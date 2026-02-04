@extends('layouts.main_layout')

@section('content')
<div class="container py-5">

    {{-- TÍTULO --}}
<div class="text-center mb-5">
<h1 class="fw-bold">Contactos</h1>
<p class="lead">

            Utilize este formulário para nos enviar dúvidas, sugestões ou reportar problemas.
</p>
</div>

    <div class="row justify-content-center">
<div class="col-lg-7">

            {{-- FORMULÁRIO --}}
<div class="card shadow-sm">
<div class="card-body p-4">

                    {{-- ALERTAS --}}

                    @if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>

                    @endif

                    @if ($errors->any())
<div class="alert alert-danger">
<strong>Erros encontrados:</strong>
<ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)
<li>{{ $error }}</li>

                                @endforeach
</ul>
</div>

                    @endif

                    <form method="POST" action="#">

                        @csrf

                        {{-- NOME --}}
<div class="mb-3">
<label class="form-label fw-semibold">Nome</label>
<input type="text" name="name" class="form-control"

                                   placeholder="O seu nome completo" required>
</div>

                        {{-- EMAIL --}}
<div class="mb-3">
<label class="form-label fw-semibold">Email CESAE</label>
<input type="email" name="email" class="form-control"

                                   placeholder="user@msft.cesae.pt" required>
</div>

                        {{-- ASSUNTO --}}
<div class="mb-3">
<label class="form-label fw-semibold">Assunto</label>
<select name="subject" class="form-select" required>
<option value="">Selecione...</option>
<option value="duvida">Dúvida</option>
<option value="sugestao">Sugestão</option>
<option value="problema">Reportar Problema</option>
<option value="outro">Outro</option>
</select>
</div>

                        {{-- REFERÊNCIA DA BOLEIA --}}
<div class="mb-3">
<label class="form-label fw-semibold">Referência da boleia (opcional)</label>
<input type="text" name="ride_reference" class="form-control"

                                   placeholder="Ex: Boleia #123">
</div>

                        {{-- MENSAGEM --}}
<div class="mb-3">
<label class="form-label fw-semibold">Mensagem</label>
<textarea name="message" rows="5" class="form-control"

                                      placeholder="Descreva a sua dúvida ou problema..." required></textarea>
</div>

                        {{-- BOTÃO --}}
<button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">

                            Enviar Mensagem
</button>

                    </form>
</div>
</div>

            {{-- INFORMAÇÕES ADICIONAIS --}}
<div class="mt-5 text-center">
<h5 class="fw-bold">O que acontece depois de enviar a mensagem?</h5>
<p class="text-muted">

                    A sua mensagem será analisada pela equipa responsável pela plataforma CESAE Boleias.

                    Caso seja necessário, entraremos em contacto através do email institucional.
</p>

                <p class="mt-3">

                    Antes de nos contactar, consulte as
<a href="{{ route('utils.how') }}" class="fw-semibold">Perguntas Frequentes</a>.
</p>
</div>

        </div>
</div>

</div>

@endsection


