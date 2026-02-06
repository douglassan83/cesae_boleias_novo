@extends('layouts.main_layout')

@section('title', 'CESAE Boleias')

@section('content')

    <!-- HERO -->
    <section class="home-hero">
        <div class="container hero-inner">
            <div class="hero-text">
                <h1>Boleias entre formandos do CESAE Digital</h1>
            </div>

            <div class="hero-image">
                <img src="{{ asset('images/hero.png') }}" alt="Boleias CESAE">
                <p>
                    Oferece ou pede boleia de forma simples, segura e exclusiva para a comunidade CESAE.
                </p>
                <a href="{{ route('login') }}" class="hero-button">
                    Entrar na plataforma
                </a>
            </div>
        </div>
    </section>

    <!-- COMO FUNCIONA -->
    <section class="home-how">
        <section class="home-how">
            <h2>Como funciona?</h2>

            <div class="how-steps">

                <a href="{{ route('users.add') }}" class="step">
                    <div class="step-circle">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <p><strong>1.</strong> Regista-te com email CESAE</p>
                </a>

                <a href="{{ route('rides.all') }}" class="step">
                    <div class="step-circle">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <p><strong>2.</strong> Oferece ou procura boleia</p>
                </a>

                <a href="https://teams.microsoft.com/" target="_blank" class="step">
                    <div class="step-circle">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <p><strong>3.</strong> Confirma e fala por Teams</p>
                </a>

            </div>
        </section>
    </section>


    <!-- PORQUÊ USAR -->
    <section class="home-why">
        <div class="why-inner">

            <!-- IMAGEM (ESQUERDA) -->
            <div class="why-image">
                <img src="{{ asset('images/carpool.png') }}" alt="Porquê usar">
            </div>

            <!-- TEXTO (DIREITA) -->
            <div class="why-text">
                <h2>Porquê usar?</h2>

                <ul>
                    <li>Sustentabilidade</li>
                    <li>Poupança</li>
                    <li>Comunidade CESAE</li>
                </ul>

                <a href="{{ route('rides.add') }}" class="why-button">
                    Partilha a tua viagem
                </a>

            </div>

        </div>
    </section>


    <!-- SEGURANÇA -->
    <section class="home-security">
        <div class="security-wrapper">
            <div class="security-inner">
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
        </div>
    </section>


    <!-- FAQ -->
    <section class="home-faq">
        <div class="faq-inner">
            <h2>
                <a href="{{ url('/como-funciona') }}" class="faq-title-link">
                    Perguntas frequentes
                </a>
            </h2>

            <ul>
                <li>Quem pode usar?</li>
                <li>É gratuito?</li>
                <li>Como falo com o motorista?</li>
                <li>O que acontece se alguém não cumprir?</li>
            </ul>
        </div>
    </section>


@endsection
