@extends('layouts.app_no_banner')
@section('content')
@include('partials._messages')
        <!-- resources/views/auth/reset.blade.php -->
<div class="container">
    <form method="POST" action="/password/reset" class="b-reset_form col-md-4 col-md-offset-4">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control"  value="{{ old('email') }}">
        </div>
        <div>
            <label for="password">Пароль</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div>
            <label for="password_confirmation">Повторите пароль</label>
            <input type="password" class="form-control" name="password_confirmation">
        </div>
        <br>
        <div>
            <button type="submit" class="btn btn-success">Подтвердить</button>
        </div>

    </form>
</div>
@endsection