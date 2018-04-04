@extends('layouts.app')

@section('js-assets')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwChXTt8eNG2IOdBFBtp5Qh2kmWwQhVng" type="text/javascript"></script>
	<script src="{{ asset('js/gmaps.js') }}"></script>
@endsection

@section('content')

	<div class="smallcompanies">
		<div class="container">
			<div class="row">

				<div class="backarrow">
					<img src="/img/png/arrwoback.png" alt="">
					<a href="{{ route('companies.index') }}">К списку компаний</a>
				</div>
				<!-- end backarrow -->

				<div class="col-md-9">
					<div class="manager">
						<div class="compani">
							<div class="img-wrapper">
								@if($company->logo)
									<img src="{{ route('imagecache', ['158_158', $company->logo]) }}" alt="">
								@else
									<img src="https://placehold.it/158x158" alt="">
								@endif
							</div>
							<div class="discriptr">
								<h3>{{ $company->title }}</h3>
								<ul>
									<li>
										<h4>Адрес</h4>
										<p>{{ $company->city && !$company->city->used_for ? $company->city->title : '' }} {{  $company->address }}</p>
										@if($company->google_map_code && $company->google_map_code != '[]')
											<a href="" class="open_modal" id="gmaps">Показать на карте</a>
										@endif
									</li>
									@if($company->fio && $company->show_fio)
										<li>
											<h4>Контактное лицо</h4>
											<p>{{ $company->fio }}</p>
										</li>
									@endif
									@if($company->phone && $company->show_phone)
									<li>
										<h4>Телефон</h4>
										<p>{{ $company->phone }}</p>
									</li>
									@endif
									@if($company->site && $company->show_site)
										<li>
											<h4>Web</h4>
											<?php $suffix = explode(':', $company->site)[0] ?>
											<p><a href="@if($suffix && ($suffix == 'http' || $suffix == 'https')){{ $company->site }}@else http://{{ $company->site }}@endif" target="_blank">{{ $company->site }}</a></p>
										</li>
									@endif
								</ul>
							</div>
						</div>
						<div class="pull-right">
							<p>Расскажите друзьям:</p>
							@include('partials._shareSocials')
						</div>
					</div>
					<!-- end manager -->
					<div class="clearfix"></div>

					<div class="iformation">
						<h3>Описание</h3>
						<p>{!! $company->about_company !!}</p>
					</div>
					<!-- end iformation -->

					<div class="clonevacan">
						<h3>Вакансии компании ({{ $company->user->vacancies()->ModeratedFixed()->count() }})</h3>
						<ul>
							@foreach($vacancies as $vacancy)
								<li>
									<a href="{{ route('vacancies.show', $vacancy->id) }}"></a>
									<div class="img-wrapper">
										@if($vacancy->user->company->logo)
											<img src="{{ route('imagecache', ['65_65', $vacancy->user->company->logo]) }}" alt="">
										@else
											<img src="https://placehold.it/65x65" alt="">
										@endif
									</div>
									<div class="title">
										<h5>{{ $vacancy->position }}</h5>
									</div>
									<div class="maps">
										@if($vacancy->city_id)
											<h4>{{ $vacancy->city->title }}</h4>
										@endif
										<p>{{ $vacancy->place_of_work }}</p>
									</div>
									<div class="times pull-right">
										@if($vacancy->busyness)
											<span>{{ $vacancy->busyness->title }}</span><br>
										@endif
										<span>Опубликовано</span><br>
										<span>{{ \Laravelrus\LocalizedCarbon\LocalizedCarbon::instance($vacancy->created_at)->diffForHumans() }}</span>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="paginations">
			<div class="container">
				<div class="row">
					<nav>
						{!! $vacancies->appends([
							/*'query' => Request::get('query'),
							'city_id' => Request::get('city_id'),
							'scope_id' => Request::get('scope_id'),
							'busyness_id' => Request::get('busyness_id'),*/
						])->render() !!}
					</nav>
				</div>
			</div>
		</div>

{{--		--}}{{--<div class="modal-body">--}}{{--
			--}}{{--<div id="map" style="width: 500px; height: 320px;"></div>--}}{{--
		--}}{{--</div>--}}{{--
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					--}}{{--<div class="modal-body">--}}{{--
						--}}{{--<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d9290635.710903395!2d40.297852!3d55.35413500000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sru!4v1465971498460"--}}{{--
						        --}}{{--width="100%" height="320px" frameborder="0" style="border:0" allowfullscreen></iframe>--}}{{--
					--}}{{--</div>--}}{{--
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					</div>
				</div>
			</div>
		</div>--}}

		<!-- Modal -->
		<div class="modal fade" id="myModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<div class="map">
							<div class="maps" id="map-container" style="height: 400px !important;"></div>
							<div style="display: none;" id="map-coords">{{ $company->google_map_code }}</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					</div>
				</div>
			</div>
		</div>

	</div> {{--smallcompanies--}}


	<script>
		window.onload = function () {
			$('.open_modal').on('click', function (e) {
				e.preventDefault();
				$('#myModal').modal('toggle').on('shown.bs.modal', function (e) {
					var markersCount = $('.markers_count');

					@if($company->google_map_code)
						<?php $geoPosition = array_first(json_decode($company->google_map_code), function($key, $value){return $key == 0;}) ?>
						map = new GMaps({
							zoom: 16,
							div: '#map-container',
							lat: "<?= $geoPosition[0] ?>",
							lng: "<?= $geoPosition[1] ?>"
						});

						strMarkers = "<?= $company->google_map_code ?>";
						var parsedMarkers = JSON.parse(strMarkers);
						$.each(parsedMarkers, function(k, v){
							map.addMarker({
								lat: v[0],
								lng: v[1],
							});
						});
						markersCount.html(map.markers.length);
					@else
						var map = new GMaps({
							div: '#map-container',
							zoom: 17,
							lat: 42.87384116352395,
							lng: 74.61088562011719
						});
					@endif

				});
			});
		}
	</script>



@endsection