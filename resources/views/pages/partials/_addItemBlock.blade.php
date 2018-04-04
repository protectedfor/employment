@if(Auth::check())
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			Добавить+
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			@if(Auth::user()->hasRoleFix('employers'))
				<li><a href="{{ route('vacancies.create') }}" style="color: #ff4800!important;">Разместить вакансию</a></li>
				<li><a href="{{ route('trainings.create') }}">Разместить курс/тренинг</a></li>
			@elseif(Auth::user()->hasRoleFix('workers'))
				<li><a href="{{ route('resumes.create') }}" style="color: #ff4800!important;">Разместить резюме</a></li>
			@endif
		</ul>
	</li>
@else
	<li><a class="ajax_modal" href="" data-action="login" data-type="auth">Добавить+</a></li>
@endif