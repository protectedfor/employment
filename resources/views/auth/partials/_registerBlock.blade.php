<div class="form-group name">
	<label for="name">ИМЯ / НАЗВАНИЕ КОМПАНИИ</label>
	<input type="text" name="name" value="{{ isset($social_user) ? array_get($social_user, 'first_name') : old('name') }}" class="form-control" id="name" placeholder="Введите ваше имя / название компании">
</div>
<div class="form-group">
	<label for="email">ВАШ E-MAIL</label>
	<input type="email" name="email" value="{{ isset($social_user) ? array_get($social_user, 'email') : old('email') }}" class="form-control" id="email" placeholder="Email">
</div>
<div class="form-group pass" style="margin-right: 10px;">
	<label for="password">ПАРОЛЬ</label>
	<input type="password" name="password" class="form-control" id="password" placeholder="Password">
</div>
<div class="form-group pass check">
	<label for="password_confirmation">ПОДТВЕРДИТЬ ПАРОЛЬ</label>
	<input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Password">
</div>