@extends('layouts.app_no_banner')
@section('content')
    @include('partials._messages')
    <div class="login">
        <div class="container">
            <div class="row">
                <h1>Войти</h1>
                <form class="col-md-offset-4 col-md-4 ajax_form" action="{{ url('auth/login') }}" method="POST">
                    {!! csrf_field() !!}
	                <input type="hidden" name="redirect_url" value="/">
                    <div class="form-group">
                        <label for="email">ВАШ E-MAIL</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="form-group pass">
                        <label for="password">ПАРОЛЬ</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    {{--<div class="checkbox">--}}
                        {{--<label>--}}
                            {{--<input type="checkbox" name="remember"> Remember Me--}}
                        {{--</label>--}}
                    {{--</div>--}}
                    <button type="submit" class="btn btn-default">Войти</button>
                </form>
                <div class="clearfix"></div>
                <div class="col-md-offset-4 col-md-4">
	                <div class="b-login-forget">
		                <a href="{{ url('password/email') }}">Забыли пароль?</a>
	                </div>
	                <div style="margin:36px 0;">
		                <h5 style="margin-bottom:4px;">Быстрая авторизация через</h5>
		                @include('auth.partials._social_auth_icons')
	                </div>
                    <p>У вас еще нет аккаунта?</p>
                    <a href="{{ url('auth/register') }}" class="reg_button">регистрация</a>
                </div>

            </div>
        </div>
    </div>
@endsection