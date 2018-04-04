<li @if(Request::url() == route('trainings.profile')) class="b-my_trainings" @endif>
	{{--@if(Request::url() == route('employers.profile.training_responses'))--}}
		{{--<div class="img-wrapper">--}}
			{{--@if($profile->logo)--}}
				{{--<img src="{{ route('imagecache', ['65_65', $profile->logo]) }}" alt="">--}}
			{{--@else--}}
				{{--<img src="https://placehold.it/65x65" alt="">--}}
			{{--@endif--}}
		{{--</div>--}}
		{{--<div class="name">--}}
			{{--<p>{{ $profile->name }} {{ $profile->sname }} {{ $profile->mname }}</p>--}}
			{{--<p>{{ $profile->phone }}</p>--}}
		{{--</div>--}}
	{{--@else--}}
		{{--@if($training->moderated)--}}
			<a href="{{ route('trainings.show', $training->id) }}" target="_blank"></a>
		{{--@else--}}
			{{--<p class="text-center" style="color: red;">Этот тренинг находится на модерации</p>--}}
		{{--@endif--}}
		<div class="img-wrapper">
			@if($training->user->company->logo)
				<img src="{{ route('imagecache', ['65_65', $training->user->company->logo]) }}" alt="">
			@else
				<img src="https://placehold.it/65x65" alt="">
			@endif
		</div>
		<div class="name">
			<p>{{ $training->title }}</p>
			{{--<p>{{ $training->coordinator }}</p>--}}
		</div>
		@if(Request::url() != route('trainings.profile') && Request::url() != route('trainings.profile.checking'))
		{{--<div class="maps">--}}
		{{--@if($training->city_id)--}}
		{{--<span>{{ $training->city->title }}</span>--}}
		{{--@endif--}}
		{{--</div>--}}
		{{--<div class="time">--}}
		{{--<p>Опубликовано {{ \Laravelrus\LocalizedCarbon\LocalizedCarbon::instance($training->created_at)->diffForHumans() }}</p>--}}
		{{--</div>--}}
		@else
			<div class="times pull-right">
				<ul id="settings">
					<li class="pencil"><a title="Редактировать" href="{{ route('trainings.edit', $training->id) }}"><img src="/img/cabine/1pen.png" alt=""></a></li>
					<li class="deletes"><a href="" title="Удалить" class="ajax_modal" data-action="delete" data-type="training" data-id="{{ $training->id }}"><img src="/img/cabine/4musr.png" alt=""></a></li>
				</ul>
				@if(Request::url() != route('employers.profile.checking'))
					@if($training->moderated)
						<a class="setting pull-right premium_post_modal" style="color: #747677; border: none">Опубликован</a>
					@else
						<a href="" class="setting pull-right ajax_modal" data-action="publish" data-type="training" data-id="{{ $training->id }}">Опубликовать</a>
					@endif
				@endif
			</div>
		@endif
	{{--@endif--}}
</li>