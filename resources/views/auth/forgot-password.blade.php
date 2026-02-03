@extends('layouts.main_layout')
@section('content')

<form method="POST" action="{{ route('password.email') }}">
        @csrf
<div class="mb-3">
<label for="exampleInputEmail1" class="form-label">Email address</label>
<input name="email" required type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
<div  id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
</div>
        @error('email')
<p class="text-danger"> Erro de email</p>
        @enderror

        <button type="submit" class="btn btn-primary">Recuperar Password</button>

    </form>

@endsection
