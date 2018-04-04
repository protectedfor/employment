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
<?php $obj = isset($notification['obj']) ? $notification['obj'] : null ?>
<?php $data = array_get($notification, 'data') ? $notification['data'] : null ?>

@if($notification['type'] == 'vacancy')
	<p>Здравствуйте, {{ $notification['recipientName'] }}!</p>
	<p style="margin-top: 40px">Вы получили новый отклик на вакансию «{{ $notification['title'] }}» от <strong>{{ $notification['senderName'] }}</strong>.<p>
	<div style="margin-top: 20px">
		<p>Посмотрите его резюме <a href="{{ route('resumes.show', $data['res']->id) }}">«{{ $data['res']->career_objective }}»</a></p>
	</div>
	<div style="margin-top: 20px">
		<p>Вы можете посмотреть все отклики в разделе <a href="{{ route('employers.profile.vacancy_responses') }}">"Отклики на вакансии"</a> в личном кабинете.</p>
	</div>
	<div style="margin-top: 40px">
		<p>Успехов в поиске профессионалов!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
	<ul style="margin: 30px 0 0 -40px; font-size: 14px;">
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('page', \App\Models\Page::findOrFail(9)->slug) }}">дополнительные возможности</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('employers.profile') }}">мои вакансии</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('resumes.index') }}">посмотреть новые резюме</a></li>
	</ul>

@elseif($notification['type'] == 'informWorker')
	<p>Здравствуйте, {{ $notification['senderName'] }}!</p>
	<p style="margin-top: 40px">Ваш отклик на вакансию «{{ $notification['title'] }}» компании «{{ $data['vac']->user->company->title }}» отправлен.<p>
	<div style="margin-top: 20px">
		<p>Вы можете следить за всеми Вашими откликами в разделе <a href="{{ route('workers.profile.vacancy_responses') }}">"Мои отклики на вакансии"</a> в личном кабинете.</p>
	</div>
	<div style="margin-top: 40px">
		<p>Успехов в поиске работы!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
	<ul style="margin: 30px 0 0 -40px; font-size: 14px;">
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('workers.profile') }}">личный кабинет</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('workers.profile') }}">мои резюме</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('vacancies.index') }}">посмотреть новые вакансии</a></li>
	</ul>

@elseif($notification['type'] == 'vacancy_form_from_file')
	<p>Здравствуйте, {{ $notification['recipientName'] }}!</p>
	<p style="margin-top: 40px">Вы получили новый отклик на вакансию «{{ $notification['title'] }}» от <strong>{{ $notification['senderName'] }}</strong> с прикреплённой заполненной формой.<p>
	<div style="margin-top: 20px">
		<p>Посмотрите его резюме <a href="{{ route('resumes.show', $data['res']->id) }}">«{{ $data['res']->career_objective }}»</a></p>
	</div>
	<div style="margin-top: 20px">
		<p>Вы можете посмотреть все отклики в разделе <a href="{{ route('employers.profile.vacancy_responses') }}">"Отклики на вакансии"</a> в личном кабинете.</p>
	</div>
	<div style="margin-top: 40px">
		<p>Успехов в поиске профессионалов!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
	<ul style="margin: 30px 0 0 -40px; font-size: 14px;">
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('page', \App\Models\Page::findOrFail(9)->slug) }}">дополнительные возможности</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('employers.profile') }}">мои вакансии</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('resumes.index') }}">посмотреть новые резюме</a></li>
	</ul>

@elseif($notification['type'] == 'resume')
	<p>Здравствуйте, {{ $notification['recipientName'] }}!</p>
	<p style="margin-top: 40px">Компания «{{ $notification['senderName'] }}» предлагает Вам работу <a href="{{ route('vacancies.show', $data['vac']->id) }}">«{{ $data['vac']->position }}»</a>.<p>
	<div style="margin-top: 20px">
		<p>Вы можете посмотреть все предложения от компаний в разделе <a href="{{ route('workers.profile.resume_responses') }}">"Предложения вакансий"</a> в своём личном кабинете.</p>
	</div>
	<div style="margin-top: 40px">
		<p>Успехов в поиске работы!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
	<ul style="margin: 30px 0 0 -40px; font-size: 14px;">
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('workers.profile') }}">личный кабинет</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('workers.profile') }}">мои резюме</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('vacancies.index') }}">посмотреть новые вакансии</a></li>
	</ul>

@elseif($notification['type'] == 'informEmployer')
	<p>Здравствуйте, {{ $notification['senderName'] }}!</p>
	<p style="margin-top: 40px">Ваше предложение по вакансии «{{ $data['vac']->position }}» отправлено соискателю {{ $notification['recipientName'] }}.<p>
	<div style="margin-top: 20px">
		<p>Вы можете следить за всеми Вашими предложениями в разделе <a href="{{ route('employers.profile.resume_responses') }}">Мои предложения вакансий</a> в личном кабинете.</p>
	</div>
	<div style="margin-top: 40px">
		<p>Успехов в поиске профессионалов!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
	<ul style="margin: 30px 0 0 -40px; font-size: 14px;">
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('page', \App\Models\Page::findOrFail(9)->slug) }}">дополнительные возможности</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('employers.profile') }}">мои вакансии</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('resumes.index') }}">посмотреть новые резюме</a></li>
	</ul>

@elseif($notification['type'] == 'training')
	<p>Здравствуйте, {{ $notification['recipientName'] }}!</p>
	<p style="margin-top: 40px">Вы получили новую заявку на курс/тренинг «{{ $notification['title'] }}».<p>
	<div style="margin-top: 20px">
		<span>Имя: {{ $notification['senderName'] }}</span><br>
		<span>Электронная почта: {{ $notification['senderEmail'] }}</span><br>
		<span>Телефон: {{ $notification['senderPhone'] }}</span><br>
	</div>
	@if($notification['senderDescription'])
		<div style="margin-top: 20px">
			<p>Сообщение: {{ $notification['senderDescription'] }}</p>
		</div>
	@endif
	<div style="margin-top: 20px">
		<p>Пожалуйста, свяжитесь и проконсультируйте о Вашем курсе/тренинге. Спасибо!</p>
	</div>
	<div style="margin-top: 40px">
		<p>Успехов с организацией курса/тренинга!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>

@elseif($notification['type'] == 'informApplicant')
	<p>Здравствуйте!</p>
	<p style="margin-top: 40px">Ваша заявка на курс/тренинг «{{ $notification['title'] }}» принята. Скоро с вами свяжутся организаторы курса/тренинга и предоставят подробную информацию о курсе/тренинге.<p>
	<div style="margin-top: 40px">
		<p>Успехов!</p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>

@elseif($notification['type'] == 'fillUpBalance')
	<p>Здравствуйте, {{ $notification['recipientName'] }}!</p>
	<p style="margin-top: 40px">Баланс Вашего лицевого счета на <a href="{{ route('home') }}">employment.kg</a> пополнен на сумму <strong>{{ $notification['sum'] }} сом</strong><p>
	<div style="margin-top: 40px">
		<p>С уважением, </p>
		<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
	</div>
	<ul style="margin: 30px 0 0 -40px; font-size: 14px;">
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('page', \App\Models\Page::findOrFail(9)->slug) }}">дополнительные возможности</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('employers.profile') }}">мои вакансии</a></li>
		<li style="display: inline-block;margin-right: 20px;"><a href="{{ route('resumes.index') }}">посмотреть новые резюме</a></li>
	</ul>

@elseif($notification['type'] == 'greetings')
	<p>Здравствуйте, {{ $notification['user']->name }}!</p>
	<p style="margin-top: 40px">Поздравляем Вас с успешной регистрацией на <a href="{{ route('home') }}">employment.kg</a>! Теперь вы можете полноценно воспользоваться всеми возможностями нашего сайта.</p>
	@if($notification['user']->roles->first()->id == 1)
		<p style="margin-top: 40px">Рекомендуем начать работу на нашем сайте с составления качественного резюме. Это даст Вам возможность подавать на вакансии, а работодателям - возможность найти и предложить вам работу</p>
		<p style="margin-top: 40px"><a href="{{ route('resumes.create') }}" style="border:1px solid #5B9BD5;line-height: 35px;display: inline-block;padding: 0 50px;border-radius: 5px;color: #fff;background-color: #5B9BD5;
	    text-decoration: none;">Разместить резюме</a><p>
		<div style="margin-top: 40px">
			<p>Также, на нашем сайте вы можете:</p>
			<ul>
				<li><a href="{{ route('vacancies.index') }}">Посмотреть новые вакансии</a></li>
				<li><a href="{{ route('trainings.index') }}">Найти необходимый обучающий курс</a></li>
				<li><a href="{{ route('articles.index') }}">Почитать полезные статьи об успешной карьере</a></li>
			</ul>
		</div>
		<div style="margin-top: 40px">
			<p>Успехов в поиске работы! </p>
			<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
		</div>
	@else
		<p style="margin-top: 40px">Рекомендуем начать работу на нашем сайте с заполнения профиля Вашей организации</p>
		<p style="margin-top: 40px"><a href="{{ route('employers.profile.edit') }}" style="border:1px solid #5B9BD5;line-height: 35px;display: inline-block;padding: 0 50px;border-radius: 5px;color: #fff;background-color: #5B9BD5;
	    text-decoration: none;">Заполнить профиль</a><p>
		<div style="margin-top: 40px">
			<p>Также, на нашем сайте вы можете:</p>
			<ul>
				<li><a href="{{ route('resumes.index') }}">Найти квалифицированных специалистов</a></li>
				<li><a href="{{ route('vacancies.create') }}">Разместить вакансии Вашей организации</a></li>
				<li><a href="{{ route('articles.index') }}">Почитать полезные статьи в сфере HR</a></li>
			</ul>
		</div>
		<div style="margin-top: 40px">
			<p>Успехов в поиске профессионалов! </p>
			<p>Команда <a href="{{ route('home') }}">employment.kg</a></p>
		</div>
	@endif

@elseif($notification['type'] == 'payment')
	<h3>Здравствуйте,</h3>
	<h3>Поступила заявка на {{ $notification['info']->type == 2 ? 'безналиный платёж' : 'наличный платёж'}} в размере {{ $notification['info']->sum }} сом. Подробная информация приведена ниже.</h3>
	<span><strong>Имя: </strong>{{ $notification['info']->name }}</span><br>
	<span><strong>Почтовый адрес: </strong>{{ $notification['info']->post }}</span><br>
	<span><strong>ИНН: </strong>{{ $notification['info']->INN }}</span><br>
	<span><strong>E-mail: </strong>{{ $notification['info']->email }}</span><br>
	<span><strong>Сумма: </strong>{{ $notification['info']->sum }}</span><br>
@endif
