@extends('layouts.app_no_banner')
@section('content')
    @include('partials._messages')
    <div class="registration">
        <div class="container">
            <div class="row">
                <h1>Регистрация</h1>
                <form class="col-md-offset-4 col-md-4" action="{{ url('auth/register') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="checks">
                        <div class="card">
                            <label class="radio">
                            <input type="radio" name="role" value="workers" @if(old('role') == 'workers') checked @endif>
                            <span class="outer">
                                <span class="inner"></span>
                            </span>Я соискатель</label>
                            <label class="radio">
                            <input type="radio" name="role" value="employers" @if(old('role') == 'employers') checked @endif>
                            <span class="outer">
                                <span class="inner"></span>
                            </span>Я работодатель</label>
                        </div>
                    </div>
	                <?php $social_user = Session::get('social_user')?>
                    <div class="form-group name">
                        <label for="name">ИМЯ / НАЗВАНИЕ КОМПАНИИ</label>
                        <input type="text" name="name" value="{{ isset($social_user) ? array_get($social_user, 'first_name') : old('name') }}" class="form-control" id="name" placeholder="Введите ваше имя / название компании">
                    </div>
                    <div class="form-group">
                        <label for="email">ВАШ E-MAIL</label>
                        <input type="email" name="email" value="{{ isset($social_user) ? array_get($social_user, 'email') : old('email') }}" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="form-group pass">
                        <label for="password">ПАРОЛЬ</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="form-group pass check">
                        <label for="password_confirmation">ПОДТВЕРДИТЬ ПАРОЛЬ</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Password">
                    </div>
	                <div class="text-center" style="margin-top:36px;">
		                <h5 style="margin-bottom:4px;">Подставить данные из</h5>
		                @include('auth.partials._social_auth_icons')
	                </div>
                    <button type="submit" class="btn btn-default">Регистрация</button>
                </form>
                <div class="clearfix"></div>
                <div class="col-md-offset-4 col-md-4 text-center">

                    <div class="b-login-forget">
                        <a href="{{ url('password/email') }}">Обновить пароль</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection