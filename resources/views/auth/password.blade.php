@extends('layouts.app_no_banner')
@section('content')
	@include('partials._messages')
	<div class="restart-pass">
		<div class="container">
			<form method="POST" action="/password/email"  class="b-login_form col-md-4 col-md-offset-4">
			    {!! csrf_field() !!}
				<h2>Восстановление пароля</h2>
			    <div>
			        <label for="email">Email</label>
			        <input class="form-control"  type="email" name="email" value="{{ old('email') }}">
			    </div>
			    <br>
			    <div>
			        <button type="submit" class="btn btn-success">Восстановить</button>
			    </div>
			</form>
		</div>
	</div>
@endsection