<ul class="nav nav-tabs">
    <li @if(Request::url() == route('employers.profile') || Request::url() == route('employers.profile.checking')) class="active" @endif>
    	<a href="{{ route('employers.profile') }}">
    		Мои вакансии ({{ $my_vacancies->count() }})
    	</a>
    </li>
    <li @if(Request::url() == route('employers.profile.vacancy_responses')) class="active" @endif>
    	<a href="{{ route('employers.profile.vacancy_responses') }}">
    		Отклики на вакансии
    	</a>
    </li>
    <li @if(Request::url() == route('employers.profile.saved_resumes')) class="active" @endif>
    	<a href="{{ route('employers.profile.saved_resumes') }}">
    		Сохраненные резюме
    	</a>
    </li>
	<li @if(Request::url() == route('employers.profile.resume_responses')) class="active" @endif>
		<a href="{{ route('employers.profile.resume_responses') }}">
			Мои предложения вакансий
		</a>
	</li>
	<li @if(Request::url() == route('page', $customPages->filter(function ($item) {return $item['id'] == 9;})->first()->slug)) class="active" @endif>
		<a href="{{ route('page', $customPages->filter(function ($item) {return $item['id'] == 9;})->first()->slug) }}" target="_blank">
			Дополнительные возможности
		</a>
	</li>
{{--	<li @if(Request::url() == route('trainings.profile')) class="active" @endif><a href="{{ route('trainings.profile') }}">Мои тренинги</a></li>--}}
{{--	<li @if(Request::url() == route('employers.profile.training_responses')) class="active" @endif><a href="{{ route('employers.profile.training_responses') }}">Отклики на тренинги</a></li>--}}
	<li @if(Request::url() == route('employers.profile.edit')) class="active" @endif>
		<a href="{{ route('employers.profile.edit') }}">
			Профиль компании
		</a>
	</li>
    <li @if(Request::url() == route('employers.profile.settings')) class="active" @endif>
    	<a href="{{ route('employers.profile.settings') }}">
    		Настройки
    	</a>
    </li>
</ul>