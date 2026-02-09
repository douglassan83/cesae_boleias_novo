<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CESAE Boleias</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- CSS Global -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- CSS Home (específico da página) -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>

   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap%27);">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-cesae">
        <div class="container-fluid">

            {{-- LOGO --}}
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('images/logo-cesae-white.png') }}" alt="CESAE Boleias" class="logo-header">
            </a>

            {{-- TOGGLER --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- MENU --}}
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                {{-- LINKS CENTRAIS --}}
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 nav-main">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('como-funciona') ? 'active' : '' }}" href="/como-funciona">
                            Como Funciona
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contactos') ? 'active' : '' }}" href="/contactos">
                            Contactos
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('rides*') ? 'active' : '' }}"
                                href="{{ route('rides.all') }}">
                                Boleias
                            </a>
                        </li>
                    @endauth

                </ul>

                {{-- LINKS À DIREITA --}}
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 nav-main">

                    @auth
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                                    href="{{ route('users.all') }}">
                                    Área de Administração
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#">
                                    Olá, {{ Auth::user()->name }}
                                </a>
                            </li>
                        @endif
                    @endauth

                </ul>


                {{-- USER AREA --}}
                <ul class="navbar-nav user-area">
                    @auth
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"
                                role="button" aria-expanded="false">


                                {{-- AVATAR --}}
                                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/nophoto.png') }}"
                                    alt="Foto de perfil" class="user-avatar">

                            </a>

                            {{-- DROPDOWN --}}
                            <ul class="dropdown-menu dropdown-menu-end dropdown-user-panel">
                                <li class="dropdown-header">
                                    {{ Auth::user()->name }}
                                </li>

                                <li>
                                    <a href="{{ route('users.view', Auth::id()) }}" class="dropdown-item dropdown-link">
                                        <span>Perfil</span>
                                        <span class="dropdown-arrow">›</span>
                                    </a>
                                </li>


                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            Terminar sessão
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown"
                                role="button" aria-expanded="false">

                                <img src="{{ asset('images/nophoto.png') }}" alt="Utilizador" class="user-avatar">
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end dropdown-user-panel">
                                <li>
                                    <a href="{{ route('login') }}" class="dropdown-item dropdown-link">
                                        <span>Iniciar Sessão</span>
                                        <span class="dropdown-arrow">›</span>
                                    </a>

                                </li>
                                <li>
                                    <a href="{{ route('users.add') }}" class="dropdown-item dropdown-link">
                                        <span>Criar Conta</span>
                                        <span class="dropdown-arrow">›</span>
                                    </a>

                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>

            </div>
        </div>
    </nav>


    <!-- CONTEÚDO -->
    <main class="main-content">
        @yield('content')
    </main>


    <!-- FOOTER -->
    <footer>

        <div class="container-fluid">

            {{-- =============================
            COMUNICAÇÕES
        ============================== --}}
            <div class="footer-communications">
                <p>
                    <strong>Comunicações da plataforma CESAE Boleias</strong><br>
                    Receba informações importantes sobre o funcionamento da plataforma
                    e regras de utilização.
                </p>

                <a href="{{ route('utils.contact') }}" class="footer-button">
                    Receber comunicação
                </a>
            </div>

            <hr>

            {{-- =============================
            CONTEÚDO PRINCIPAL
        ============================== --}}
            <div class="footer-main">

                {{-- TEXTO + LOGO --}}
                <div class="footer-text">
                    <img src="{{ asset('images/logo-cesae-white.png') }}" alt="CESAE Boleias" class="footer-logo">

                    <p>
                        Plataforma exclusiva para formandos do CESAE Digital,
                        criada para facilitar a partilha de boleias de forma simples,
                        segura e colaborativa.
                    </p>

                    <div class="footer-socials">

                        <a href="https://www.instagram.com/cesae.digital/" class="social-circle" target="_blank"
                            rel="noopener noreferrer" aria-label="Instagram CESAE Digital">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="https://www.facebook.com/CESAE.Digital" class="social-circle" target="_blank"
                            rel="noopener noreferrer" aria-label="Facebook CESAE Digital">
                            <i class="bi bi-facebook"></i>
                        </a>

                        <a href="https://www.youtube.com/@cesaedigital" class="social-circle" target="_blank"
                            rel="noopener noreferrer" aria-label="YouTube CESAE Digital">
                            <i class="bi bi-youtube"></i>
                        </a>

                        <a href="https://www.linkedin.com/school/cesae-digital/" class="social-circle"
                            target="_blank" rel="noopener noreferrer" aria-label="LinkedIn CESAE Digital">
                            <i class="bi bi-linkedin"></i>
                        </a>

                    </div>


                </div>

                {{-- ILUSTRAÇÃO --}}
                <div class="footer-illustration">
                    <img src="{{ asset('images/footer-illustration.png') }}" alt="Ilustração CESAE Boleias">
                </div>

            </div>

            <hr>

            {{-- =============================
            COPYRIGHT
        ============================== --}}
            <div class="footer-bottom">
                © 2026 CESAE Boleias. Todos os direitos reservados.
            </div>

        </div>

    </footer>


    @stack('scripts')

</body>

</html>
