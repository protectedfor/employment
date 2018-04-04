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

@if(array_get($user, 'activated') == 1)
	<p>Здравствуйте, {{ array_get($user, 'name') }}!</p>
	<p style="margin-top: 40px">Если Вы хотите восстановить пароль, нажмите на кнопку ниже и сбросьте пароль:<p>
	<p><a href="{{ url('password/reset/'.$token) }}" style="border:1px solid #5B9BD5;line-height: 35px;display: inline-block;padding: 0 50px;border-radius: 5px;color: #fff;background-color: #5B9BD5; text-decoration: none;">Сбросить пароль</a></p>
	<div style="margin-top: 20px">
		<p>Если Вам не удается нажать на кнопку «Сбросить пароль», перейдите по следующей ссылке: <a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a></p>
	</div>
	<div style="margin-top: 20px">
		<p>Если Вы не запрашивали восстановление пароля на сайте employment.kg, просто проигнорируйте данное сообщение.</p>
	</div>
	<div style="margin-top: 40px">
		<p>С уважением, </p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
@elseif(array_get($user, 'activated') == 0 && $user->roles->first()->name == "workers")
	<p>Уважаемый(ая) {{ array_get($user, 'name') }}!</p>
	<p style="margin-top: 40px">Мы рады сообщить Вам о запуске нашего нового сайта <a href="{{ route('home') }}">employment.kg</a>!</p>
	<p style="margin-top: 40px">С новыми возможностями для поиска работы наш сервис стал еще круче!</p>
	<p style="margin-top: 40px">На протяжении 2-х лет мы провели тщательную работу по исследованию практики поиска работы в Кыргызстане. В результате мы определили ключевые аспекты, влияющие на эффективность и успешность поиска работы, и внедрили их в наш обновленный сайт. </p>
	<p style="margin-top: 40px">Но, к нашему огромному сожалению, мы не сможем перенести ваши размещенные резюме на новую версию сайта в связи с тем, что формы заполнения резюме новой версии отличаются от старой версии сайта. Мы приносим свои извинения за то, что Вам придется заново разместить резюме на сайте.</p>
	<p style="margin-top: 40px">Как новая версия сайта поможет лично Вам?</p>
	<ul>
		<li>Размещая резюме, Вы экономите свое время и облегчаете себе задачу при подаче заявок на различные вакансии (особенно, когда вы подаете на несколько вакансий);</li>
		<li>Ваше резюме будет включать всю необходимую информацию, которую ожидают увидеть работодатели;</li>
		<li>Это дает Вам отличную возможность сразу же привлечь внимание более <strong>4 000</strong> работодателей на нашем сайте.</li>
	</ul>
	<p style="margin-top: 40px">Перейдите на сайт и начните искать работу своей мечты! Для безопасности система предложит Вам обновить свой пароль.</p>
	<p style="margin-top: 40px"><a href="{{ route('password.reset', $token) }}" style="border:1px solid #5B9BD5;line-height: 35px;display: inline-block;padding: 0 50px;border-radius: 5px;color: #fff;background-color: #5B9BD5;
    text-decoration: none;">Перейти на сайт</a><p>
	<p style="margin-top: 40px">Если у вас возникли вопросы по регистрации, мы будем рады на них ответить.</p>
	<div style="margin-top: 40px">
		<p>С уважением,</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
@elseif(array_get($user, 'activated') == 0 && $user->roles->first()->name == "employers")
	<p>Уважаемый(ая) {{ array_get($user, 'name') }}!</p>
	<p style="margin-top: 40px">Мы рады сообщить Вам о запуске нашего нового сайта <a href="{{ route('home') }}">employment.kg</a>!</p>
	<p style="margin-top: 40px">С новыми возможностями для HR наш сервис стал еще круче!</p>
	<p style="margin-top: 40px">Мы уверены, что c новым сайтом мы сможем предоставить Вам незаменимый инструмент для эффективного поиска нужных сотрудников и быстрого закрытия вакансий.</p>
	<p style="margin-top: 40px">Нашим сайтом уже пользуются более <strong>4 000</strong> работодателей Кыргызстана и успешно находят квалифицированных сотрудников.</p>
	<p style="margin-top: 40px">Перейдите на сайт и начните работу по подбору сотрудников! Для безопасности cистема предложит Вам обновить свой пароль.</p>
	<p style="margin-top: 40px"><a href="{{ route('password.reset', $token) }}" style="border:1px solid #5B9BD5;line-height: 35px;display: inline-block;padding: 0 50px;border-radius: 5px;color: #fff;background-color: #5B9BD5;
    text-decoration: none;">Перейти на сайт</a><p>
	<p style="margin-top: 40px">Если у вас возникли вопросы по регистрации, мы будем рады на них ответить.</p>
	<div style="margin-top: 40px">
		<p>С уважением,</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
@endif