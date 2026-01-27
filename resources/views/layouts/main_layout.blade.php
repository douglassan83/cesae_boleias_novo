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

    <!-- CSS Projeto (DEPOIS do Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>
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
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Como Funciona</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">Contactos</a>
                    </li>
                </ul>

                {{-- USER AREA --}}
                <ul class="navbar-nav user-area">
                    @auth
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                data-bs-toggle="dropdown">

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
                                    <a href="{{ route('users.view', Auth::id()) }}" class="dropdown-item">
                                        Perfil
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
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer>

        <div class="container">

            {{-- =============================
            COMUNICAÇÕES
        ============================== --}}
            <div class="footer-communications">
                <p>
                    <strong>Comunicações da plataforma CESAE Boleias</strong><br>
                    Receba informações importantes sobre o funcionamento da plataforma
                    e regras de utilização.
                </p>

                <a href="#" class="footer-button">
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
                        <a href="#" class="social-circle" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-circle" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-circle" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="social-circle" aria-label="LinkedIn">
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



</body>

</html>
