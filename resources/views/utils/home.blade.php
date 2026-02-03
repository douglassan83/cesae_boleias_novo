@extends('layouts.main_layout')

@section('title', 'CESAE Boleias')

@section('content')

<!-- HERO -->
<section class="home-hero">
    <div class="container hero-inner">
        <div class="hero-text">
            <h1>Boleias entre formandos do CESAE Digital</h1>
            <p>
                Oferece ou pede boleia de forma simples, segura e exclusiva para a comunidade CESAE.
            </p>
            <a href="{{ route('login') }}" class="hero-button">
                Entrar na plataforma
            </a>
        </div>

        <div class="hero-image">
            <img src="{{ asset('images/hero.png') }}" alt="Boleias CESAE">
        </div>
    </div>
</section>

<!-- COMO FUNCIONA -->
<section class="home-how">
    <div class="container">
        <h2 class="text-center">Como funciona?</h2>

        <div class="how-steps">
            <div class="step">
                <div class="step-circle">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <p>Regista-te com email CESAE</p>
            </div>

            <div class="step">
                <div class="step-circle">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <p>Oferece ou procura boleia</p>
            </div>

            <div class="step">
                <div class="step-circle">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <p>Confirma e fala por WhatsApp</p>
            </div>
        </div>
    </div>
</section>

<!-- PORQUÊ USAR -->
<section class="home-why">
    <div class="why-inner">
        <div class="why-text">
            <h2>Porquê usar?</h2>
            <ul>
                <li>Sustentabilidade</li>
                <li>Poupança</li>
                <li>Comunidade CESAE</li>
            </ul>
        </div>

        <div class="why-image">
            <img src="{{ asset('images/carpool.png') }}" alt="Porquê usar">
        </div>
    </div>
</section>

<!-- SEGURANÇA -->
<section class="home-security">
    <div class="container security-inner">
        <div class="security-text">
            <h2>Segurança</h2>
            <ul>
                <li>Email institucional validado</li>
                <li>Perfis identificados</li>
                <li>Contacto direto entre utilizadores</li>
            </ul>
        </div>

        <div class="security-image">
            <img src="{{ asset('images/security.png') }}" alt="Segurança">
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="home-faq">
    <div class="container">
        <h2>Perguntas frequentes</h2>
        <ul>
            <li>Quem pode usar?</li>
            <li>É gratuito?</li>
            <li>Como falo com o motorista?</li>
            <li>O que acontece se alguém não cumprir?</li>
        </ul>
    </div>
</section>

@endsection
