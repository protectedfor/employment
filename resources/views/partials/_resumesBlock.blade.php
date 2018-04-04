<li class="@if($resume->is_fixed)active @endif @if(isset($key) && ($key % 2) == 0)even @endif">
	<a @if(Auth::user() && (Auth::user()->hasRoleFix('employers') || (Auth::user()->hasRoleFix('workers') && $resume->user_id === Auth::id())))
	        href="{{ route('resumes.show', $resume->id) }}" target="_blank"
	   @elseif(Auth::user() && Auth::user()->hasRoleFix('workers'))
	        class="ajax_modal" href="" data-action="show" data-type="text"
	   @else
	        class="ajax_modal" href="" data-action="login" data-type="auth" data-parameters="headerText"
		@endif
	>
		@if(!$resume->moderated && !$resume->draft)<p style="color: red; text-align: center;">Это резюме находится на модерации</p>@endif
	</a>
	<div class="img-wrapper">
		@if($resume->photo)
			<img src="{{ route('imagecache', ['65_65', $resume->photo]) }}" alt="">
		@else
			<img src="https://placehold.it/65x65" alt="">
		@endif
	</div>
	<div class="name">
		<p>{{ $resume->career_objective }}</p>
		<span>{{ $resume->name }}, {{ $resume->date_of_birth->age }} {{ trans_choice('год|года|лет', $resume->date_of_birth->age) }}, опыт работы {{ $resume->totalWorkExperienceInRussian() }}</span>
	</div>
	@if(Request::url() != route('workers.profile') && Request::url() != route('workers.profile.checking') && Request::url() != route('workers.profile.drafts'))
		@if(Request::url() == route('resumes.index') || Request::url() == route('ajax.resumes'))
			<div class="maps">
				@if($resume->city)
					<span>{{ $resume->city->title }}</span>
				@endif
			</div>
		@endif
		<div class="skill">
			@if($resume->resumeField)
				<p>{{ $resume->resumeField->title }}</p>
			@endif
		</div>
		@if(Request::url() == route('employers.profile.vacancy_responses') && isset($response) && ($response->file1 || $response->file2 || $response->file3 || $response->form_from_file))
			<div class="time">
				<a href="" class="attached_modal" data-id="{{ $response->id }}">Прикреплённые файлы</a>
			</div>
		@endif
		<div class="time">
			@if($resume->busyness)
				<span>{{ $resume->busyness->title }}</span>
			@endif
			<p>Опубликована {{ \Laravelrus\LocalizedCarbon\LocalizedCarbon::instance($resume->created_at)->diffForHumans() }}</p>
		</div>
		@if(Request::url() == route('employers.profile.saved_resumes') || Request::url() == route('employers.profile.vacancy_responses'))
			<div class="saves">
				@if(Request::url() == route('employers.profile.saved_resumes'))
					<a href="" title="Удалить" class="red ajax_modal" data-action="delete" data-type="resume" data-id="{{ $resume->id }}" data-parameters="favourites">Удалить</a>
				@elseif(Request::url() == route('employers.profile.vacancy_responses'))
					<a href="" title="Удалить" class="red delete_modal ajax_modal" data-action="delete" data-type="vacancy" data-id="{{ $vacancy->id }}" data-forItem="resume" data-forItem-id="{{ $resume->id }}" data-parameters="responses">Удалить</a>
				@endif
			</div>
		@endif
	@else
		<div class="times pull-right">
			<ul id="settings">
				<li class="pencil"><a title="Редактировать" href="{{ route('resumes.edit', $resume->id) }}"><img  src="/img/cabine/1pen.png" alt=""></a></li>
				<li class="donwload"><a title="Сохранить" href="{{ route('resumes.getPdf', $resume->id) }}"><img  src="/img/cabine/3down.png" alt=""></a></li>
				<li class="deletes"><a href="" title="Удалить" class="ajax_modal" data-action="delete" data-type="resume" data-id="{{ $resume->id }}"><img src="/img/cabine/4musr.png" alt=""></a></li>
				@if($resume->is_hidden)
					<li class="res-hid res-hidden" data-id="{{ $resume->id }}"><a class="hid-link_{{ $resume->id }}" title="Показывать в списке резюме" href=""><img  class="hid-img_{{ $resume->id }}" style="opacity: 0.3;" src="/img/png/eyes.png" alt=""></a></li>
				@else
					<li class="res-hid res-visible" data-id="{{ $resume->id }}"><a class="hid-link_{{ $resume->id }}" title="Скрыть в списке резюме" href=""><img  class="hid-img_{{ $resume->id }}" src="/img/png/eyes.png" alt=""></a></li>
				@endif
			</ul>
		</div>
	@endif
</li>

@if(Request::url() == route('workers.profile') || Request::url() == route('workers.profile.checking') || Request::url() == route('workers.profile.drafts'))
	<div class="modal fade" id="show_modal_{{ $resume->id }}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Резюме отображается в общем списке резюме</h4>
				</div>
				<div class="modal-footer">
					{{--<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>--}}
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="hide_modal_{{ $resume->id }}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Резюме скрыто из общего списка резюме</h4>
				</div>
				<div class="modal-footer">
					{{--<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>--}}
				</div>
			</div>
		</div>
	</div>
@endif

@if(Request::url() == route('employers.profile.vacancy_responses'))
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