@extends('layouts.main_layout')
@section('content')
    <br>

    <section class="container d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-sm border-0">
                <div id = "login-header" class="card-header p-3">
                    <img src="{{ asset('images/nophoto.png') }}" alt="Foto de perfil"
                        class="rounded-circle shadow-sm user-avatar mx-auto d-block mb-2">



                </div>


                <div class="card-body p-4">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input name="email" required type="email"
                                class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text"></div>
                        </div>
                        @error('email')
                            <p class="text-danger small">Erro de email</p>
                        @enderror

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input required name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1">
                        </div>
                        @error('password')
                            <p class="text-danger small">Erro de password</p>
                        @enderror

                        <div class="d-grid mb-3">
                            <button type="submit" id = "login-button" class="btn text-white">
                                Login
                            </button>
                        </div>

                        <p class="mb-0 text-center">
                            Esqueceu-se da password? clique
                            <a href="{{ route('password.request') }}">aqui</a>
                        </p>
                    </form>

                </div>
            </div>
        </div>
    </section>
    <br>
@endsection
