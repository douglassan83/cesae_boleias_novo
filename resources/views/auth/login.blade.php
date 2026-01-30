@extends('layouts.main_layout')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow" style="width: 400px; min-height: 400px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-4">Login</h3>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                        autofocus
                    >
                </div>

                {{-- PASSWORD --}}
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                    >
                </div>

                {{-- BOTÃO --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                </div>

                {{-- ESQUECEU PASSWORD --}}
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}">
                        Esqueceu-se da password?
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection


