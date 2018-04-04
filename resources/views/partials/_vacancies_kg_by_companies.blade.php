<div class="category">
	<h2>Вакансии в компаниях</h2>
	<ul>
		@foreach($companiesKg as $companyKg)
			<li>
				<a href="{{ route('vacancies.index', ['user_id' =>$companyKg->user_id]) }}">
					<span class="pull-right">{{ $companyKg->user->vacancies->where('moderated', 1)->count() }}</span>
					{{ $companyKg->title }}
				</a>
			</li>
		@endforeach
	</ul>
</div>