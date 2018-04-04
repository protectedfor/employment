<style>
	body {
		font-family: DejaVu Sans;
	}
	a {
		text-decoration: none;
		font-weight: bold;
		color: #5B9BD5;
	}
</style>

<p>Здравствуйте, {{ array_get($user, 'name') }}!</p>
<p style="margin-top: 40px">Пожалуйста, активируйте Ваш аккаунт нажав на кнопку ниже:<p>
<p><a href="{{ $activation_link }}" style="border:1px solid #5B9BD5;line-height: 35px;display: inline-block;padding: 0 50px;border-radius: 5px;color: #fff;background-color: #5B9BD5; text-decoration: none;">Активировать аккаунт</a></p>
<div style="margin-top: 20px">
	<p>Если Вам не удается нажать на кнопку «Активировать аккаунт», вставьте следующую ссылку в адресную строку Вашего браузера: <a href="{{ $activation_link }}">{{ $activation_link }}</a></p>
</div>
<div style="margin-top: 40px">
	<p>С уважением, </p>
	<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
</div>