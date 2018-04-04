<li class="@if($vacancy->billingFilter('makeFixed')->count() > 0 && \Carbon\Carbon::now() < $vacancy->billingFilter('makeFixed')->sortByDesc('updated_at')->first()->updated_at->addDays(7))
    active @endif @if(Request::url() == route('workers.profile.saved_vacancies')) saved_vacancies @endif">
	<a href="{{ route('vacancies.show', $vacancy->id) }}" target="_blank">@if(!$vacancy->moderated && !$vacancy->draft)<p style="color: red; text-align: center;">Эта вакансия находится на модерации </p>@endif</a>
	<div class="img-wrapper">
		@if($vacancy->user->company->logo)
			<img src="{{ route('imagecache', ['65_65', $vacancy->user->company->logo]) }}" alt="">
		@else
			<img src="https://placehold.it/65x65" alt="">
		@endif
	</div>
	<div class="name">
		<p>{{ $vacancy->position }}</p>
		@if($vacancy->user->company)
			<span>{{ $vacancy->user->company->title }}</span>
		@endif
	</div>
	@if(Request::url() != route('employers.profile') && Request::url() != route('employers.profile.checking') && Request::url() != route('employers.profile.drafts'))
		<div class="maps">
			@if($vacancy->city_id)
				<span>{{ $vacancy->city->title }}</span>
			@endif
		</div>
		@if(Request::url() == route('workers.profile.vacancy_responses') && isset($response) && ($response->file1 || $response->file2 || $response->file3 || $response->form_from_file))
			<div class="time">
				<a href="" class="attached_modal" data-id="{{ $response->id }}">Прикреплённые файлы</a>
			</div>
		@endif
		<div class="time">
			@if($vacancy->busyness)
				<div class="index-box">
					<span>{{ $vacancy->busyness->title }}</span>
				</div>
			@endif
			<p>Опубликована {{ \Laravelrus\LocalizedCarbon\LocalizedCarbon::instance($vacancy->created_at)->diffForHumans() }}</p>
		</div>
		@if(Request::url() == route('workers.profile.saved_vacancies') || Request::url() == route('workers.profile.resume_responses'))
			<div class="saves">
				@if(Request::url() == route('workers.profile.saved_vacancies'))
					<a href="" title="Удалить" class="red ajax_modal" data-action="delete" data-type="vacancy" data-id="{{ $vacancy->id }}" data-parameters="favourites">Удалить</a>
				@elseif(Request::url() == route('workers.profile.resume_responses'))
					<a href="" title="Удалить" class="red delete_modal ajax_modal" data-action="delete" data-type="resume" data-id="{{ $resume->id }}" data-forItem="vacancy" data-forItem-id="{{ $vacancy->id }}" data-parameters="responses">Удалить</a>
				@endif
			</div>
		@endif
	@else
		<div class="times pull-right">
			<ul id="settings">
				<li class="pencil" ><a title="Редактировать" href="{{ route('vacancies.edit', $vacancy->id) }}"><img src="/img/cabine/1pen.png" alt=""></a></li>
				<li class="donwload"><a title="Сохранить" href="{{ route('vacancies.getPdf', $vacancy->id) }}"><img src="/img/cabine/3down.png" alt=""></a></li>
				<li class="deletes"><a href="" title="Удалить" class="ajax_modal" data-action="delete" data-type="vacancy" data-id="{{ $vacancy->id }}"><img src="/img/cabine/4musr.png" alt=""></a></li>
			</ul>
			@if(Request::url() != route('employers.profile.checking') && Request::url() != route('employers.profile.drafts'))
				<a href="" class="setting ajax_modal" data-action="makeInPriority" data-type="vacancy" data-id="{{ $vacancy->id }}">Поднять в списке</a>
				<a href="" class="setting ajax_modal" data-action="makeFixed" data-type="vacancy" data-id="{{ $vacancy->id }}">Прикрепить в списке</a>
				<a href="" class="setting ajax_modal" data-action="makeHot" data-type="vacancy" data-id="{{ $vacancy->id }}">В горячие</a>
			@endif
		</div>
	@endif
</li>

@if(Request::url() == route('workers.profile.vacancy_responses'))
	<div class="modal fade" id="attachedModal_{{ $response->id }}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Прикреплённые к отклику файлы</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						@if(isset($response) && $response->form_from_file)
							<p id="first">Заполненная форма<span>({{ round(filesize(config('admin.imagesUploadDirectory'). '/attached/'. $response->form_from_file)/1000) }}кб)</span><a href="{{ url(config('admin.imagesUploadDirectory'). '/attached/'. $response->form_from_file) }}"> Скачать</a></p>
						@endif
						@if(isset($response) && $response->file1)
							<p id="first">{{ $response->filename1 }}<span>({{ round(filesize(config('admin.imagesUploadDirectory'). '/attached/'. $response->file1)/1000) }}кб)</span><a href="{{ url(config('admin.imagesUploadDirectory'). '/attached/'. $response->file1) }}"> Скачать</a></p>
						@endif
						@if(isset($response) && $response->file2)
							<p id="first">{{ $response->filename2 }}<span>({{ round(filesize(config('admin.imagesUploadDirectory'). '/attached/'. $response->file2)/1000) }}кб)</span><a href="{{ url(config('admin.imagesUploadDirectory'). '/attached/'. $response->file2) }}"> Скачать</a></p>
						@endif
						@if(isset($response) && $response->file3)
							<p id="first">{{ $response->filename3 }}<span>({{ round(filesize(config('admin.imagesUploadDirectory'). '/attached/'. $response->file3)/1000) }}кб)</span><a href="{{ url(config('admin.imagesUploadDirectory'). '/attached/'. $response->file3) }}"> Скачать</a></p>
						@endif
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary send_response">Удалить</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				</div>
			</div>
		</div>
	</div>
@endif