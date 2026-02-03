@extends('layouts.main_layout')

@section('content')
@section('content')
    <div class="cesae-hero">

        <img src="{{ asset('images/imagem-hero.png') }}" class="cesae-hero-bg" alt="Boleias CESAE">


        <div class="cesae-hero-text text-center text-white">
            <h1 class="display-4 fw-bold mb-4">Boleia entre a comunidade do Cesae Digital</h1>
            <h3 class="lead mb-4">Oferece ou pede boleia de forma simples, segura<br>e exclusiva para a comunidade CESAE.
            </h3>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">Entrar na plataforma</a>
            </div>
            <a href="{{ route('users.add') }}" class="text-white text-decoration-none fw-medium">Não tem registro? clique
                aqui</a>
        </div>
    </div>
@endsection
@endsection
