@if(Request::url() == route('ajax.vacancies'))
	<ul>
		<?php $afterBannerVacancies = $vacancies->splice(5); ?>
		@foreach($vacancies as $vacancy)
			@include('partials._vacanciesBlock')
		@endforeach
		@include('partials._bannersBlock', ['position' => 'vacancies_all_center', 'size' => '1170_100', 'wrapperClass' => 'vacancies_all_center_banner'])
	    @foreach($afterBannerVacancies as $vacancy)
	        @include('partials._vacanciesBlock')
	    @endforeach
	</ul>
	<div class="total" style="display: none;">{{ $vacancies->total() }} {{ trans_choice('вакансия|вакансии|вакансий', $vacancies->total()) }}</div>
	<div class="show_load_more" style="display: none;">{{ $vacancies->hasMorePages() ? '1' : '0' }}</div>
@elseif(Request::url() == route('ajax.resumes'))
	<ul>
		@ReplaceBlock('partials._allResumesBlock')
	</ul>
	<div class="total" style="display: none;">{{ $resumes->total() }} {{ trans_choice('резюме|резюме|резюме', $resumes->total()) }}</div>
	<div class="show_load_more" style="display: none;">{{ $resumes->hasMorePages() ? '1' : '0' }}</div>
@elseif(Request::url() == route('ajax.companies'))
	<ul>
		@foreach($companies as $company)
			<li class="">
				<a href="{{ route('companies.show', $company->id) }}" target="_blank"></a>
				<div class="img-wrapper">
					@if($company->logo)
						<img src="{{ route('imagecache', ['65_65', $company->logo]) }}" alt="">
					@else
						<img src="https://placehold.it/65x65" alt="">
					@endif
				</div>
				<div class="name">
					<p>{{ $company->title }}</p>
				</div>
				<div class="skill">
					@if($company->scope)
						<p>{{ $company->scope->title }}</p>
					@endif
				</div>
				<div class="maps">
					@if($company->city && !$company->city->used_for)
						<span>{{ $company->city->title }}</span>
					@endif
				</div>
				<div class="time">
					@if($company->user)
						<span>{{ $company->user->vacancies->filter(function ($item) {return $item['moderated'] == true;})->count() }} {{ trans_choice('вакансия|вакансии|вакансий', $company->user->vacancies->filter(function ($item) {return $item['moderated'] == true;})->count()) }}</span>
					@endif
				</div>
			</li>
		@endforeach
	</ul>
	<div class="total" style="display: none;">{{ $companies->total() }} {{ trans_choice('компания|компании|компаний', $companies->total()) }}</div>
	<div class="show_load_more" style="display: none;">{{ $companies->hasMorePages() ? '1' : '0' }}</div>
@endif
