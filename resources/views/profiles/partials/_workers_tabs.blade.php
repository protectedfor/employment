<ul class="nav nav-tabs">
    <li @if(Request::url() == route('workers.profile')) class="active" @endif><a href="{{ route('workers.profile') }}">Мои резюме ({{ $my_resumes->count() }})</a></li>
    <li @if(Request::url() == route('workers.profile.vacancy_responses')) class="active" @endif><a href="{{ route('workers.profile.vacancy_responses') }}">Мои отклики на вакансии</a></li>
    <li @if(Request::url() == route('workers.profile.saved_vacancies')) class="active" @endif><a href="{{ route('workers.profile.saved_vacancies') }}">Сохраненные вакансии</a></li>
	<li @if(Request::url() == route('workers.profile.resume_responses')) class="active" @endif><a href="{{ route('workers.profile.resume_responses') }}">Предложения вакансий</a></li>
{{--	<li @if(Request::url() == route('workers.profile.training_responses')) class="active" @endif><a href="{{ route('workers.profile.training_responses') }}">Мои отклики на тренинги</a></li>--}}
	<li @if(Request::url() == route('workers.profile.edit')) class="active" @endif><a href="{{ route('workers.profile.edit') }}">Мой профиль</a></li>
	<li @if(Request::url() == route('workers.profile.settings')) class="active" @endif><a href="{{ route('workers.profile.settings') }}">Настройки</a></li>
</ul>