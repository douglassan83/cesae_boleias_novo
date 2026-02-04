@extends('layouts.main_layout')
@section('content')
<br>
<h3>LOGIN</h3>
<form method="POST" action="{{ route('login') }}">
        @csrf
<div class="mb-3">
<label for="exampleInputEmail1" class="form-label">Email address</label>
<input name="email" required type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
<div  id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
</div>
        @error('email')
<p class="text-danger"> Erro de email</p>
        @enderror
<div class="mb-3">
<label for="exampleInputPassword1" class="form-label">Password</label>
<input required name="password" type="password" class="form-control" id="exampleInputPassword1">
</div>
        @error('password')
<p class="text-danger"> Erro de password</p>
        @enderror



        <button type="submit" class="btn btn-primary">Login</button>

        <p>Esqueceu-se da password? clique <a href="{{route('password.request')}}">aqui</a></p>
</form>
@endsection

