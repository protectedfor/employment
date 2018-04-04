@extends('layouts.app')
@section('js-assets')
	<script src="{{ asset('js/flow.min.js') }}"></script>
@endsection
@section('content')
    <div class="smallvakansia">
        <div class="container">
            <div class="row">

                <div class="backarrow">
                    <img src="/img/png/arrwoback.png" alt="">
                    <a href="{{ route('vacancies.index') }}">К списку вакансий</a>
                </div> <!-- end backarrow -->
	            @if ($vacancy->draft && $vacancy->moderated)
		            <p style="color: red;">Это режим предварительного просмотра. Чтобы опубликовать нажмите "Опубликовать эту вакансию"</p>
	            @elseif($vacancy->draft && !$vacancy->moderated)
					<p style="color: red;">Это режим предварительного просмотра. Для подтверждения отправки на модерацию нажмите "Опубликовать эту вакансию"</p>
	            @endif
	            
                <div class="col-md-9">
                    <div class="manager">
	                    @include('vacancies.partials._showVacancyInfo', ['published' => 'Опубликована', 'deadline' => 'Крайний срок', 'expired' => 'Истёк срок подачи заявок ',
	                    'left' => 'осталось', 'days' => 'дней', 'salary' => 'Зарплата', 'perInterview' => 'По результатам собеседования ', 'workExperience' => 'Опыт работы',
	                    'city' => 'Город', 'business' => 'Занятость', 'schedule' => 'График работы'])

	                    @if (!$vacancy->draft)
	                        <div class="pull-right">
		                        @if(($vacancy->expires_at != null && \Carbon\Carbon::now() > $vacancy->expires_at->addDay()) ||
		                            ($vacancy->expires_at == null && \Carbon\Carbon::now() > $vacancy->created_at->addDays(30)))
			                        <p class="pull">Истёк срок подачи заявок</p>
		                        @else
			                        @if($vacancy->request_type != 'online_form')
				                        <a class="pull response_modal" href=""><img src="/img/social/report.png" alt="">Откликнуться</a>
			                        @else
				                        <a href="{{ $vacancy->link_online_form }}" class="pull" target="_blank"><img src="/img/social/report.png" alt="">Перейти</a>
			                        @endif
		                        @endif
		                        @if (Auth::check() && Auth::user()->hasRoleFix('workers') && Auth::user()->savedVacancies()->where('id', $vacancy->id)->count() == 0)
			                        <a class="save" href="{{ route('vacancies.toFavourites', $vacancy->id) }}"><img src="/img/social/star.png" alt="">Сохранить вакансию</a>
		                        @elseif (Auth::check() && Auth::user()->hasRoleFix('workers'))
			                        <a class="save" href="{{ route('vacancies.toFavourites', [$vacancy->id, 'action' => 'remove']) }}"><img src="/img/social/star.png" alt="">Убрать из "Сохранённого"</a>
		                        @endif
	                            <p>Расскажите друзьям:</p>
		                        @include('partials._shareSocials')
	                        </div>
	                    @endif
                    </div> <!-- end manager -->

	                @include('vacancies.partials._showInformation', ['info' => 'Информация', 'commonInfo' => 'Общие сведения', 'requirements' => 'Требования ',
					'duties' => 'Обязанности', 'conditions' => 'Условия', 'aboutCompany' => 'О компании'])

                    <div class="sendsms">
	                    @if (!$vacancy->draft)
		                    <div class="topline">
	                            <a href="" class="warning complain_modal">Пожаловаться</a>
		                        <div class="pull-right">
			                        @if (Auth::check() && Auth::user()->hasRoleFix('workers') && Auth::user()->savedVacancies()->where('id', $vacancy->id)->count() == 0)
				                        <a class="save" href="{{ route('vacancies.toFavourites', $vacancy->id) }}"><img src="/img/button/star.png" alt="">Сохранить вакансию</a>
			                        @elseif (Auth::check() && Auth::user()->hasRoleFix('workers'))
				                        <a class="save" href="{{ route('vacancies.toFavourites', [$vacancy->id, 'action' => 'remove']) }}"><img src="/img/button/star.png" alt="">Убрать из "Сохранённого"</a>
			                        @endif
			                        <a onClick="window.print()"><img src="/img/button/print.png" alt="">Распечатать</a>
			                        <a href="{{ route('vacancies.getPdf', $vacancy->id) }}"><img src="/img/button/pdf.png" alt="">PDF</a>
			                        {{--<a href=""><img src="/img/button/doc.png" alt="">Word</a>--}}
		                        </div>
	                        </div>
		                    <div class="downloadds text-center">
			                    @if(isset($expired_vacancy))
				                    <h4>Истёк срок подачи заявок</h4>
			                    @else
				                    @if($vacancy->request_type == 'resume')
					                    <h4>ВНИМАНИЕ! Для подачи заявки на данную вакансию, Вам необходимо откликнутся и прикрепить требуемые документы</h4>
					                    {!! $vacancy->only_in_english ? '<span style="color: red;"">ВНИМАНИЕ! Заявка на данную вакансию должна быть на английском языке</span>' : '' !!}
					                    <a href="" id="send_vacanc" class="response_modal">Откликнуться</a>
				                    @elseif($vacancy->request_type == 'form_from_file')
					                    <h4>ВНИМАНИЕ! Для подачи заявки на данную вакансию, Вам необходимо скачать файл ниже, заполнить его и прикрепить к своему отклику</h4>
					                    <p id="first" style="text-align: center;">{{ $vacancy->form_from_file }}<a style="float: none;" href="{{ url(config('admin.imagesUploadDirectory'). '/'. $vacancy->form_from_file) }}">Скачать <span>({{ round(filesize(config('admin.imagesUploadDirectory'). '/'. $vacancy->form_from_file)/1000) }}кб)</span></a></p>
					                    {!! $vacancy->only_in_english ? '<span style="color: red;"">ВНИМАНИЕ! Заявка на данную вакансию должна быть на английском языке</span>' : '' !!}
					                    <a href="" id="send_vacanc" class="response_modal">Откликнуться</a>
				                    @else
					                    <h4>ВНИМАНИЕ! Для подачи заявки на данную вакансию, Вам необходимо перейти на страницу Работодателя и заполнить онлайн форму. Желаем успехов с подачей заявки!</h4>
					                    {!! $vacancy->only_in_english ? '<span style="color: red;"">ВНИМАНИЕ! Заявка на данную вакансию должна быть на английском языке</span>' : '' !!}
					                    <a href="{{ $vacancy->link_online_form }}" id="send_vacanc" target="_blank">Перейти</a>
				                    @endif
			                    @endif
		                    </div>
	                    @else
		                    <div class="downloadds">
			                    <div class="pull-left">
			                        <a href="{{ route('vacancies.edit', $vacancy->id) }}" id="send_vacanc">Редактировать</a>
			                    </div>
			                    <div class="pull-right">
			                        <a href="{{ route('vacancies.publishDraft', $vacancy->id) }}" id="send_vacanc">Опубликовать эту вакансию</a>
			                    </div>
		                    </div>
	                    @endif
                    </div>
                    @if(count($similars) > 0 && !$vacancy->draft)
                        <div class="clonevacan">
                            <h3>Похожие вакансии</h3>
                            <ul>
                                @foreach($similars as $vacancy)
		                            @include('partials._vacanciesBlock')
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
	                @include('vacancies.partials._showCompanyInfo', ['address' => 'Адрес', 'contactPerson' => 'Контактное лицо', 'phone' => 'Телефон'])
                    @if(count($vacancies) > 0)
                        <h3>Другие вакансии компании</h3>
                        <ul>
                            @foreach($vacancies as $vac)
                                <li>
                                    <p><a href="{{ route('vacancies.show', $vac->id) }}">{{ $vac->position }}</a></p>
                                    <span>{{ $vac->place_of_work }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
	                @include('partials._bannersBlock', ['position' => 'vacancies_right', 'size' => '260_500', 'wrapperClass' => 'vacancies_right_banner'])
                </div>
            </div>
        </div>
    </div>

    @if($currentVacancy->only_in_english)
	    <?php $trueCondition = Auth::check() && Auth::user()->hasRoleFix('workers') && $currentVacancy->request_type != 'online_form' && Auth::user()->resumes()->notDraft()->where('language_id', 2)->get()->count() > 0 ?>
	    <?php $falseCondition = Auth::check() && Auth::user()->hasRoleFix('workers') && Auth::user()->resumes()->notDraft()->where('language_id', 2)->get()->count() < 1 ?>
    @else
	    <?php $trueCondition = Auth::check() && Auth::user()->hasRoleFix('workers') && $currentVacancy->request_type != 'online_form' && Auth::user()->resumes()->notDraft()->where('language_id', 1)->get()->count() > 0 ?>
	    <?php $falseCondition = Auth::check() && Auth::user()->hasRoleFix('workers') && Auth::user()->resumes()->notDraft()->where('language_id', 1)->get()->count() < 1 ?>
    @endif

    @if($trueCondition && $currentVacancy->request_type == 'form_from_file')
	    <div class="modal fade facansia" id="responseModal">
		    <div class="modal-dialog" role="document">
			    <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title" id="myModalLabel">Для отправки заявки на эту вакансию необходимо заполнить и прикрепить форму предложенную работодателем</h4>
				    </div>
				    <form action="{{ route('vacancy.response.send', ['type' => 'vacancy_form_from_file']) }}" method="POST" enctype="multipart/form-data" class="response_form">
					    <div class="modal-body">
						    {!! csrf_field() !!}
						    {!! Form::hidden('vacancy_id', $currentVacancy->getKey()) !!}

						    <div class="form-group vacansia-margin">
							    {!! Form::select('resume_id', $currentVacancy->only_in_english ? Auth::user()->resumes()->notDraft()->where('language_id', 2)->get()->lists('career_objective', 'id') : Auth::user()->resumes()->notDraft()->where('language_id', 1)->get()->lists('career_objective', 'id'), '', ['class' => 'form-control']) !!}

						    </div>
						    <label for="" style="text-align: left; padding: 0 10px!important;">Прикрепите файл с заполненной формой:</label>
						    <div class="form-group b-form-file_upload">
							    <label for="file"></label>
							    <div class="uploadFile one vacansia" data-target="{{ route('ajax.uploadFile', ['section' => 'form_from_file']) }}" data-token="{{ csrf_token() }}">
								    <div>
									    <div class="thumbnail">
										    <div class="no-value">
											    <span>Файл не загружен</span>
										    </div>
										    <div class="has-value hidden">
											    <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
										    </div>
									    </div>
								    </div>
								    <div class="one-vacansia">
									    <div class="btn btn-primary imageBrowse"><i class="fa fa-upload"></i>Выберите файл<input class="b-button-upload_video" type="file"></div>
									    <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i> Удалить</div>
								    </div>
								    <input name="form_from_file" class="imageValue" type="hidden" value="">
								    <div class="errors"></div>
							    </div>
						    </div>
					    </div>
					    <label for="" style="text-align: left;margin-top: 25px; padding: 0 10px">Если необходимо, прикрепите файлы дополнительно:</label>
					    @for($i = 1; $i <= 3; $i++)
						    <div class="form-group vacansia-margin">
						    <div class="b-form-file_upload">
								    <label for="file"></label>
								    <div class="uploadFile one vacansia" data-target="{{ route('ajax.uploadFile', ['section' => 'response_attached_file']) }}" data-token="{{ csrf_token() }}">
									    <div>
										    <div class="thumbnail">
											    <div class="no-value">
												    <span>Файл {{ $i }} не загружен</span>
											    </div>
											    <div class="has-value hidden">
												    <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
											    </div>
										    </div>
									    </div>
									    <div class="one-vacansia">
										    <div class="btn btn-primary imageBrowse"><i class="fa fa-upload"></i>Выберите файл<input class="b-button-upload_video" type="file"></div>
										    <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i> Удалить</div>
									    </div>
									    <input name="file{{ $i }}" class="imageValue" type="hidden" value="">
									    <div class="errors"></div>
								    </div>
							    <input name="filename{{ $i }}" class="form-control" type="text" value="Файл{{ $i }}" placeholder="Введите название файла {{ $i }}. Например: “Сертификат”">
							    </div>
						    </div>
					    @endfor
					    <div class="modal-footer">
						    <button type="submit" class="btn btn-primary send_response">Откликнуться</button>
					    </div>
				    </form>
			    </div>
		    </div>
	    </div>
    @elseif($trueCondition)
        <div class="modal fade" id="responseModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Для отправки заявки на эту вакансию, пожалуйста, выберите резюме {{ $currentVacancy->only_in_english ? 'на английском языке' : 'на русском языке' }}</h4>
                    </div>
                    <form action="{{ route('vacancy.response.send') }}" method="POST" enctype="multipart/form-data" class="response_form">
                        <div class="modal-body">
                            {!! csrf_field() !!}
                            {!! Form::hidden('vacancy_id', $currentVacancy->getKey()) !!}
                            <div class="form-group">
                                {!! Form::select('resume_id', $currentVacancy->only_in_english ? Auth::user()->resumes()->notDraft()->where('language_id', 2)->get()->lists('career_objective', 'id') : Auth::user()->resumes()->notDraft()->where('language_id', 1)->get()->lists('career_objective', 'id'), '', ['class' => 'form-control']) !!}
                            </div>
	                        <label for="" style="text-align: center;margin-top: 25px;">Если необходимо, прикрепите файлы дополнительно:</label>
	                        @for($i = 1; $i <= 3; $i++)
		                        <div class="form-group vacansia-margin">
			                        <div class="b-form-file_upload">
				                        <label for="file"></label>
				                        <div class="uploadFile one vacansia" data-target="{{ route('ajax.uploadFile', ['section' => 'response_attached_file']) }}" data-token="{{ csrf_token() }}">
					                        <div>
						                        <div class="thumbnail">
							                        <div class="no-value">
								                        <span>Файл {{ $i }} не загружен</span>
							                        </div>
							                        <div class="has-value hidden">
								                        <a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
							                        </div>
						                        </div>
					                        </div>
					                        <div class="one-vacansia">
						                        <div class="btn btn-primary imageBrowse"><i class="fa fa-upload"></i>Выберите файл<input class="b-button-upload_video" type="file"></div>
						                        <div class="btn btn-danger imageRemove"><i class="fa fa-times"></i> Удалить</div>
					                        </div>
					                        <input name="file{{ $i }}" class="imageValue" type="hidden" value="">
					                        <div class="errors"></div>
				                        </div>
			                        </div>
			                        <input name="filename{{ $i }}" class="form-control" type="text" value="Файл{{ $i }}" placeholder="Введите название файла {{ $i }}. Например: “Сертификат”">
		                        </div>
	                        @endfor
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary send_response">Откликнуться</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @elseif($falseCondition)
        <div class="modal fade" id="responseModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Для отклика на эту вакансию необходимо иметь резюме <strong><u>{{ $currentVacancy->only_in_english ? 'на английском языке ' : 'на русском языке ' }}</u></strong></h4>
	                    <i>Примечание: Если вы, размещая свое резюме, не хотите, чтобы работодатели могли просматривать его, вы можете всегда скрыть свое резюме от общего доступа</i>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Пожалуйста, добавьте хотя бы одно <a href="{{ route('resumes.create') }}">резюме</a> <strong><u>{{ $currentVacancy->only_in_english ? 'на английском языке' : 'на русском языке' }}</u></strong> для отклика на эту вакансию</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="modal fade" id="responseModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Пожалуйста, авторизуйтесь</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Пожалуйста, <a href="{{ route('auth.login') }}">авторизуйтесь</a> как соискатель для отклика на эту вакансию</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(Auth::check() && Auth::user()->hasRoleFix('workers'))
	    <div class="modal fade" id="complainModal">
		    <div class="modal-dialog" role="document">
			    <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title" id="myModalLabel">Чтобы пожаловаться на эту вакансию, пожалуйста, выберите причину</h4>
				    </div>
				    <form action="{{ route('vacancies.complain') }}" method="POST" enctype="multipart/form-data" class="response_form">
					    <div class="modal-body">
						    {!! csrf_field() !!}
						    {!! Form::hidden('vacancy_id', $currentVacancy->getKey()) !!}
						    <label for="">Причина:</label>
						    <div class="form-group">
							    {!! Form::select('complain_id', \App\Models\Vacancies\Complain::all()->lists('title', 'id')->all(), '', ['class' => 'form-control']) !!}
						    </div>
						    <label for="">Описание:</label>
						    <div class="form-group">
							    {!! Form::textarea('description', '', ['class' => 'form-control']) !!}
						    </div>
					    </div>
					    <div class="modal-footer">
						    <button type="submit" class="btn btn-primary send_response">Пожаловаться</button>
						    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
					    </div>
				    </form>
			    </div>
		    </div>
	    </div>
    @else
	    <div class="modal fade" id="complainModal">
		    <div class="modal-dialog" role="document">
			    <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title" id="myModalLabel">Пожалуйста, авторизуйтесь</h4>
				    </div>
				    <div class="modal-body">
					    <div class="form-group">
						    <p>Пожалуйста, авторизуйтесь как соискатель чтобы пожаловаться на эту вакансию</p>
					    </div>
				    </div>
				    <div class="modal-footer">
					    <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
				    </div>
			    </div>
		    </div>
	    </div>
    @endif
@stop