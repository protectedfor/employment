@if(Auth::check())
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			{{ str_limit(Auth::user()->name, 15) }}
			<span class="caret"></span>
			@if(Auth::user()->hasRoleFix('employers') && Auth::user()->allResponses('vacancies') > 0 )
				<a href="{{ route('employers.profile.vacancy_responses')}}" class="b-responses_amount" title="Новые отклики">
					<span class="badge" style="background-color: darkorange;">{{ Auth::user()->allResponses('vacancies') }}</span>
				</a>
			@elseif(Auth::user()->hasRoleFix('workers') && Auth::user()->allResponses('resumes') > 0 )
				<a href="{{ route('workers.profile.resume_responses')}}" class="b-responses_amount" title="Новые предложения">
					<span class="badge" style="background-color: darkorange;">{{ Auth::user()->allResponses('resumes') }}</span>
				</a>
			@endif
		</a>
		<ul class="dropdown-menu">
			<li>Ваш лицевой счет: {{ Auth::user()->personal_bill }}</li>
			@if(Auth::user()->hasRoleFix('employers'))
				<li><a href="{{ route('vacancies.create') }}" style="color: #ff4800!important;">Разместить вакансию</a></li>
				<li><a href="{{ route('employers.profile') }}">Личный кабинет</a></li>
			@elseif(Auth::user()->hasRoleFix('workers'))
				<li><a href="{{ route('resumes.create') }}" style="color: #ff4800!important;">Разместить резюме</a></li>
				<li><a href="{{ route('workers.profile') }}">Личный кабинет</a></li>
			@endif
			<li><a class="ajax_replace" href="{{ url('auth/logout') }}">Выход</a></li>
		</ul>
	</li>
@else
	<li><a class="ajax_modal" href="" data-action="login" data-type="auth">Вход/Регистрация</a></li>
@endif