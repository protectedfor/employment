@extends('layouts.app')
@section('content')
    <div class="onerezume">
        <div class="container">
            <div class="row">

                <div class="backarrow">
                    <img src="/img/png/arrwoback.png" alt="">
                    <a href="">Резюме</a>
                </div> <!-- end backarrow -->
	            @if ($resume->draft && $resume->moderated)
		            <p style="color: red;">Это режим предварительного просмотра. Для публикации нажмите "Опубликовать это резюме"</p>
	            @elseif($resume->draft && !$resume->moderated)
		            <p style="color: red;">Это режим предварительного просмотра. Для подтверждения отправки на модерацию нажмите "Опубликовать это резюме"</p>
	            @endif

                <div class="col-md-9">
                    @include('resumes.partials._showUserInfo', ['published' => 'Опубликовано', 'phone' => 'Телефон', 'age' => 'Возраст',
	                'city' => 'Город', 'business' => 'Занятость', 'fieldOfActivity' => 'Сфера деятельности', 'salary' => 'Желаемая зарплата'])

	                @if (!$resume->draft)
	                    <div class="pull-right" id="social">
	                        <a class="pull response_modal" href=""><img src="/img/social/report.png" alt="">Предложить вакансию</a>
		                    @if($resume->phone && isset(Auth::user()->company) && !Auth::user()->company->get_contacts)
		                        <a class="contacts ajax_modal" href="" data-action="getContacts" data-type="company"><img src="/img/social/tele.png" alt="/img/social/tele.png">Получить Контакты</a>
		                    @endif
	                        @if(!Auth::check())
	                            <a class="save" href="{{ url('auth/login') }}"><img src="/img/social/star.png" alt="">Сохранить резюме</a>
	                        @endif
		                    @if (Auth::check() && Auth::user()->hasRoleFix('employers') && Auth::user()->savedResumes()->where('id', $resume->id)->count() == 0)
			                    <a class="save" href="{{ route('resumes.toFavourites', $resume->id) }}"><img src="/img/social/star.png" alt="">Сохранить резюме</a>
		                    @elseif (Auth::check() && Auth::user()->hasRoleFix('employers'))
			                    <a class="save" href="{{ route('resumes.toFavourites', [$resume->id, 'action' => 'remove']) }}"><img src="/img/social/star.png" alt="">Убрать из "Сохранённого"</a>
		                    @endif
	                        <p>Расскажите друзьям:</p>
		                    @include('partials._shareSocials')
	                    </div> <!-- end pull-right -->
					@endif
                    <div class="clearfix"></div>

	                @include('resumes.partials._showWorkExperience', ['workExperience' => 'Опыт работы', 'tillNow' => 'по настоящее время',
	                'fieldOfActivity' => 'Сфера деятельности', 'website' => 'Сайт', 'placeOfWork' => 'Место работы', 'responsibilities' => 'Обязанности'])

	                @include('resumes.partials._showEducation', ['education' => 'Образование', 'faculty' => 'Факультет', 'levelOfEducation' => 'Уровень образования'])

	                @include('resumes.partials._showExtraEducation', ['extraEducation' => 'Дополнительное образование'])

	                @include('resumes.partials._showAdditionInformation', ['additionalInfo' => 'Дополнительная информация', 'aboutMe' => 'Обо мне',
	                'languages' => 'Знание языков', 'nativeLanguage' => 'Родной язык', 'files' => 'Файлы', 'kb' => 'кб'])


                    <div class="sendsms">
                        @if (!$resume->draft)
                            <div class="topline">
                                <a href="" class="warning complain_modal">Пожаловаться</a>
                                <div class="pull-right">
	                                @if(!Auth::check())
		                                <a class="save" href="{{ url('auth/login') }}"><img src="/img/social/star.png" alt="">Сохранить резюме</a>
	                                @endif
	                                @if (Auth::check() && Auth::user()->hasRoleFix('employers') && Auth::user()->savedResumes()->where('id', $resume->id)->count() == 0)
		                                <a class="save" href="{{ route('resumes.toFavourites', $resume->id) }}"><img src="/img/social/star.png" alt="">Сохранить резюме</a>
	                                @elseif (Auth::check() && Auth::user()->hasRoleFix('employers'))
		                                <a class="save" href="{{ route('resumes.toFavourites', [$resume->id, 'action' => 'remove']) }}"><img src="/img/social/star.png" alt="">Убрать из "Сохранённого"</a>
	                                @endif
                                    <a onClick="window.print()"><img src="/img/button/print.png" alt="">Распечатать</a>
                                    <a href="{{ route('resumes.getPdf', $resume->id) }}" rel="nofollow"><img src="/img/button/pdf.png" alt="">PDF</a>
                                    {{--<a href=""><img src="/img/button/doc.png" alt="">Word</a>--}}
                                </div>
                            </div>
                            <div class="downloadds">
	                            @if($resume->phone && isset(Auth::user()->company) && !Auth::user()->company->get_contacts)
	                                <div class="text-center">
		                                <a class="ajax_modal" href="" data-action="getContacts" data-type="company">Получить Контакты</a>
	                                    <p>Получив контакты, Вы сможете моментально связаться с соискателем и пригласить на собеседование</p>
	                                </div>
	                            @endif
                                <div class="text-center">
                                    <a class="response_modal" href="">Предложить вакансию</a>
                                    <p>Соискатель получит Ваше предложение и сможет откликнуться на указанную Вами вакансию</p>
                                </div>
                            </div>
                        @else
	                        <div class="downloadds">
		                        <div class="pull-left">
                                    <a class="sendresums" href="{{ route('resumes.edit', $resume->id) }}">Редактировать</a>
		                        </div>
		                        <div class="pull-right">
	                                <a class="sendresums" href="{{ route('resumes.publishDraft', $resume->id) }}">Опубликовать это резюме</a>
		                        </div>
	                        </div>
						@endif
                    </div> <!-- end sendsms -->
                </div> <!-- end col-md-9 -->
	            @if(count($similars) > 0 && !$resume->draft)
	                <div class="col-md-3 b-similars">
	                    <h2>Похожие резюме</h2>
	                    <ul>
		                    @foreach($similars as $similar)
		                        <li>
			                        <a href="{{ route('resumes.show', $similar->id) }}"><h4>{{ $similar->career_objective }}</h4></a>
		                            <span>{{ $similar->fullTitle }}, опыт работы {{ $similar->totalWorkExperienceInRussian() }}</span>
			                        @if($similar->city)
		                                <span>{{ $similar->city->title }}</span>
									@endif
		                        </li>
							@endforeach
	                    </ul>
	                </div>
				@endif
            </div> <!-- end row -->
        </div> <!-- end container -->
    </div> <!-- end onerezume -->

    @if(Auth::check() && Auth::user()->hasRoleFix('employers') && $listedVacancies->count() > 0)
	    <div class="modal fade" id="responseModal">
		    <div class="modal-dialog" role="document">
			    <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title" id="myModalLabel">Предложите вакансию для текущего резюме</h4>
				    </div>
				    <form action="{{ route('resume.response.send') }}" method="POST" enctype="multipart/form-data" class="response_form">
					    <div class="modal-body">
						    {!! csrf_field() !!}
						    {!! Form::hidden('resume_id', $resume->getKey()) !!}
						    <div class="form-group">
							    <select name="vacancy_id" class="form-control">
								    @foreach($listedVacancies as $listedVacancy)
									    <option @if($listedVacancy->newPosition) disabled @endif value="{{ $listedVacancy->id }}">{{ $listedVacancy->newPosition ? $listedVacancy->newPosition : $listedVacancy->position }}</option>
								    @endforeach
							    </select>
						    </div>
					    </div>
					    <div class="modal-footer">
						    <button type="submit" class="btn btn-primary send_response">Предложить вакансию</button>
						    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
					    </div>
				    </form>
			    </div>
		    </div>
	    </div>
    @elseif(Auth::check() && Auth::user()->hasRoleFix('employers') && $listedVacancies->count() < 1)
	    <div class="modal fade" id="responseModal">
		    <div class="modal-dialog" role="document">
			    <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title" id="myModalLabel">У вас нет ни одной активной вакансии для предложения</h4>
				    </div>
				    <div class="modal-body">
					    <div class="form-group">
						    <p>Пожалуйста, добавьте хотя бы одну <a href="{{ route('vacancies.create') }}">вакансию</a> для предложения</p>
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
						    <p>Пожалуйста, авторизуйтесь как работодатель для предложения своей вакансии</p>
					    </div>
				    </div>
				    <div class="modal-footer">
					    <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
				    </div>
			    </div>
		    </div>
	    </div>
    @endif

    @if(Auth::check() && Auth::user()->hasRoleFix('employers'))
	    <div class="modal fade" id="complainModal">
		    <div class="modal-dialog" role="document">
			    <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title" id="myModalLabel">Чтобы пожаловаться на это резюме, пожалуйста, выберите причину</h4>
				    </div>
				    <form action="{{ route('resumes.complain') }}" method="POST" enctype="multipart/form-data" class="response_form">
					    <div class="modal-body">
						    {!! csrf_field() !!}
						    {!! Form::hidden('resume_id', $resume->getKey()) !!}
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
						    <p>Пожалуйста, авторизуйтесь как работодатель чтобы пожаловаться на это резюме</p>
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