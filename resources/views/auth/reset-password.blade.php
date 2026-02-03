@extends('layouts.main_layout')
@section('content')

<form method="POST" action="{{ route('password.update') }}">
        @csrf
<div class="mb-3">
<label for="exampleInputEmail1" class="form-label">Email address</label>
<input value="{{ request()->email }}" name="email" required type="email" class="form-control"
                id="exampleInputEmail1" aria-describedby="emailHelp">
<div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
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
<div class="mb-3">
<label for="exampleInputPassword1" class="form-label">Password</label>
<input required name="password_confirmation" type="password" class="form-control" id="exampleInputPassword1">
</div>
        @error('password-confirmation')
<p class="text-danger"> Erro de password</p>
        @enderror



        <input hidden type="text" name="token" value="{{ request()->route('token') }}">
<button type="submit" class="btn btn-primary">Reset</button>



    </form>


@endsection
