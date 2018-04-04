@extends('layouts.app')
@section('js-assets')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwChXTt8eNG2IOdBFBtp5Qh2kmWwQhVng" type="text/javascript"></script>
	<script src="{{ asset('js/gmaps.js') }}"></script>
@endsection
@section('content')
    <div class="smallvakansia">
        <div class="container">
            <div class="row">

                <div class="backarrow">
                    <img src="/img/png/arrwoback.png" alt="">
                    <a href="{{ route('trainings.index') }}">К списку тренингов</a>
                </div> <!-- end backarrow -->

	            <div class="one-vuz">
		            <div class="container">
			            <div class="row">
				            <div class="col-md-3">
								@include('partials._educationBlock')
					            @include('partials._bannersBlock', ['position' => 'trainings_left', 'size' => '260_500', 'wrapperClass' => 'trainings_left_banner'])
				            </div>
				            <div class="col-md-9">
					            <div class="abouts">
						            <h3>{{ $training->title }}</h3>
						            <ul class="content">
							            <li><p>Опубликовано:</p> <span>{{ $training->created_at->format('d.m.Y H:i:s') }}</span></li>
							            <li><p>Название компании:</p> <span>{{ $training->coordinator }}</span></li>
							            <li><p>Крайний срок подачи заявки</p>
								            @if($training->expires_at == null)
									          <span>{{ $training->created_at->addDays(30)->format('d.m.Y') }} 
  												<i>@if(\Carbon\Carbon::now() > $training->created_at->addDays(30)) истёк срок подачи заявок @else осталось {{ \Carbon\Carbon::now()->diffInDays($training->created_at->copy()->addDays(30)) }} дней @endif</i>
									          </span>
								            @else
								            <span>
									           {{ $training->expires_at->format('d.m.Y') }}
										            <i>@if(\Carbon\Carbon::now() > \Carbon\Carbon::createFromTimestamp(strtotime($training->expires_at))) истёк срок подачи заявок @else осталось {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::createFromTimestamp(strtotime($training->expires_at))) }} дней @endif</i>
									         </span>
							                @endif
							            </li>
							            <li><p>Стоимость обучения:</p><span>{{ $training->price }}</span></li>
							            <li><p>Дата начала:</p><span>{{ $training->start_date }}</span></li>
							            @if($training->duration)
							                <li><p>Продолжительность:</p><span>{{ $training->duration }}</span></li>
							            @endif
							            @if($training->schedule)
							                <li><p>Время проведения занятий:</p><span>{{ $training->schedule }}</span></li>
							            @endif
							            @if($training->place)
							                <li><p>Место проведения: </p><span>{{ $training->place }}</span></li>
							            @endif
						            </ul>
						            <div class="right-element">
							            <div class="img-wrapper">
								            @if($training->user->company->count() > 0 && $training->user->company->logo)
									            <img src="{{ route('imagecache', ['170_170', $training->user->company->logo]) }}" alt="">
								            @else
									            <img src="https://placehold.it/170x170" alt="">
								            @endif
							            </div>
							            <div class="social">
								            <p>Расскажите друзьям:</p>
								            @include('partials._shareSocials')
							            </div>
						            </div>
					            </div>
					            <div class="discript">
						            <h4>Описание курса/тренинга</h4>
						            @if($training->photo)
							            <img src="{{ route('imagecache', ['articles_show', $training->photo]) }}" alt="">
						            @else
							            <img src="https://placehold.it/800x800" alt="">
						            @endif
									{!! $training->description !!}
						            @if($training->contacter || $training->coach || $training->phone || $training->email || $training->site || $training->address)
							            <h4>Контакты</h4>
							            <ul class="contact">
								            @if($training->contacter)
									            <li>
										            <h5>Контактное лицо:</h5>
										            <h6>{{ $training->contacter }}</h6>
									            </li>
								            @endif
								            @if($training->coach)
									            <li>
										            <h5>Тренер:</h5>
										            <h6>{{ $training->coach }}</h6>
									            </li>
								            @endif
								            @if($training->phone)
									            <li>
										            <h5>Телефон:</h5>
										            <h6>{{ $training->phone }}</h6>
									            </li>
								            @endif
								            @if($training->email)
									            <li>
										            <h5>Email:</h5>
										            <h6>{{ $training->email }}</h6>
									            </li>
								            @endif
								            @if($training->site)
									            <li>
										            <h5>Сайт:</h5>
										            <h6>{{ $training->site }}</h6>
									            </li>
								            @endif
								            @if($training->address)
									            <li>
										            <h5>Адрес :</h5>
										            <h6>{{ $training->address }}</h6>
									            </li>
								            @endif
							            </ul>
						            @endif
						            @if($training->google_map_code && $training->google_map_code != '[]')
							            <h4>Положение на карте</h4>
							            <div class="form-group ">
								            <div id="map" style="width: 800px; height: 200px;"></div>
							            </div>
						            @endif
						            @if($training->location == 0)
							            <div class="click">
								            <a href="" class="response_modal">Подать заявку</a>
								            <p>после подачи заявки мы сами с Вами свяжемся в ближайшее рабочее время и ответим на все Ваши вопросы</p>
							            </div>
						            @endif
					            </div>
				            </div>
			            </div>
		            </div>
	            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="responseModal">
	    <div class="modal-dialog" role="document">
		    <div class="modal-content">
{{--			    @if(!Auth::check() || (Auth::check() && $training->user_id != Auth::id() && Auth::user()->hasRoleFix('workers')))--}}
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					    <h4 class="modal-title">Чтобы подать заявку заполните форму</h4>
				    </div>
				    <form action="{{ route('training.response.send', $training->id) }}" method="POST" enctype="multipart/form-data" class="response_form">
					    <div class="modal-body">
						    {!! csrf_field() !!}
						    <div class="form-group">
							    <label for="">Имя:</label>
							    {!! Form::text('name', Auth::check() && Auth::user()->hasRoleFix('workers') ? Auth::user()->profile->name : null, ['class' => 'form-control', 'required']) !!}
						    </div>
						    <div class="form-group">
							    <label for="">E-mail:</label>
							    {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
						    </div>
						    <div class="form-group">
							    <label for="">Телефон:</label>
							    {!! Form::text('phone', Auth::check() && Auth::user()->hasRoleFix('workers') ? Auth::user()->profile->phone : null, ['class' => 'form-control', 'required']) !!}
						    </div>
						    <div class="form-group">
							    <label for="">Текст сообщения:</label>
							    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5]) !!}
						    </div>
					    </div>
					    <div class="modal-footer">
						    <button type="submit" class="btn btn-primary send_response">Подать заявку</button>
<!-- 						    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button> -->
					    </div>
				    </form>
				{{--@else--}}
				    {{--<div class="modal-header">--}}
					    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
					    {{--<h4 class="modal-title">Работодатели не могут подавать заявки на тренинг</h4>--}}
				    {{--</div>--}}
				    {{--<div class="modal-footer">--}}
					    {{--<button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>--}}
				    {{--</div>--}}
				{{--@endif--}}
		    </div>
	    </div>
    </div>

    <script>
	    window.onload = function () {

		    $(function (e) {

			    markersInput = $('*[name=google_map_code]');
			    var markersCount = $('.markers_count');

			    @if($training->google_map_code)
				    <?php $geoPosition = array_first(json_decode($training->google_map_code), function($key, $value){return $key == 0;}) ?>
					map = new GMaps({
					    zoom: 16,
					    div: '#map',
					    lat: "<?= $geoPosition[0] ?>",
					    lng: "<?= $geoPosition[1] ?>"
					    });

					strMarkers = "<?= $training->google_map_code ?>";
				    var parsedMarkers = JSON.parse(strMarkers);

				    $.each(parsedMarkers, function (k, v) {
					    map.addMarker({
						    lat: v[0],
						    lng: v[1],
					    });
				    });

				    markersCount.html(map.markers.length);
				@else
					var markersCount = $('.markers_count');
				    map = new GMaps({
					    zoom: 16,
					    div: '#map',
					    lat: 42.87384116352395,
					    lng: 74.61088562011719
				    });
			    @endif
		    });
	    }
    </script>

@endsection