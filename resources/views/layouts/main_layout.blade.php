<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    {{-- Bootstrap Icons (para o ícone de usuário) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous" defer>
    </script>
</head>

<body>
    {{-- NAVBAR BOOSTSTRAP --}}
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img src="{{ asset('images/logo-cesae-white.png') }}" width="145px"
                    height="60px"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- MENU 1: Home (/) - Todos veem --}}
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/">Como Funciona</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/">Contactos</a>
                    </li>



                    @auth {{-- SÓ LOGADOS --}}
                        {{-- MENU 2: Oferecer (rides.add) - Motorista --}}
                        @if (Auth::user()->role == 'user-motorista')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('rides.add') }}">Oferecer boleia</a>
                            </li>
                        @endif

                        {{-- MENU 3: Todas Boleias (rides.all) - Todos logados --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rides.all') }}">Todas as boleias</a>
                        </li>

                        {{-- MENU 4: Minhas Boleias (rides.my_requests) - Todos logados --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rides.my_requests') }}">Minhas boleias</a>
                        </li>

                        {{-- MENU 5: Admin (users.all?) --}}
                        @if (Auth::user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.all') }}">Gerenciar users</a>
                            </li>
                        @endif
                    @endauth



                </ul>






                {{-- DROPDOWN USUÁRIO (LOGIN / REGISTER / LOGOUT) --}}
                @if (Route::has('login'))
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle fs-3 me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    {{-- BADGE ROLE (ADMIN) --}}
                                    @if (Auth::user()->role == 'admin')
                                        <li><span class="dropdown-item-text text-warning fw-semibold">CONTA ADMIN</span>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif

                                    {{-- PERFIL - Ver MEUS dados (users.view/{id}) --}}
                                    <li>
                                        <a href="{{ route('users.view', Auth::id()) }}" class="dropdown-item">
                                            <i class="bi bi-person me-2"></i>Perfil {{-- UserController@viewUser --}}
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                        </hr>
                                    </li>

                                    {{-- LOGOUT --}}
                                    <li>
                                        <form action="{{ route('logout') }}" method="post" class="px-3 py-1">
                                            @csrf
                                            <button class="btn btn-danger w-100" type="submit">Logout</button>
                                        </form>
                                    </li>
                                </ul>

                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="{{ route('login') }}" class="dropdown-item">
                                            Log in
                                        </a>
                                    </li>
                                    @if (Route::has('users.add'))
                                        <li>
                                            <a href="{{ route('users.add') }}" class="dropdown-item">
                                                Register
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endauth
                    </ul>
                @endif
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <footer class="fixed-bottom"> Meu footer aqui</footer>


</html>
