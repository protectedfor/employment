@extends('layouts.app_no_banner')
@section('js-assets')
	<script>
		function getUrlVars() {
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
				vars[key] = value;
			});
			return vars;
		}

		$(function(){
			var modal_show = getUrlVars()["modal_show"];
			if (modal_show) {
				$('#premiumPostModal_' + modal_show).modal('toggle')
			}
		});
	</script>
@endsection
@include('profiles.partials._employer_assets')
@section('content')
    <div class="room">
        <div class="container">
            <div class="row">
                @include('profiles.partials._company_block')
                <div class="col-md-9">
                    <!-- Tab panes -->
	                <div class="tab-content">
		                <div role="tabpanel" class="tab-pane fade in active" id="vakansia">

			                <div class="title">
				                <div class="pull-left">
					                <h2>Мои тренинги</h2>
				                </div>
				                {{--<div class="pull-right">--}}
					                {{--@if(Request::url() == route('trainings.profile')) <p>На сайте <span>({{ $my_trainings->count() }})</span></p> @else <a href="{{ route('trainings.profile') }}">На сайте ({{ $my_trainings->count() }})</a> @endif--}}
					                {{--@if(Request::url() == route('trainings.profile.checking')) <p>На проверке <span>({{ $checking_trainings->count() }})</span></p> @else <a href="{{ route('trainings.profile.checking') }}">На проверке ({{ $checking_trainings->count() }})</a> @endif--}}
				                {{--</div>--}}
			                </div>

			                <div class="categorys text-center">
				                <a href="{{ route('trainings.create') }}" class="addmore">+ Добавить тренинг</a>
				                {{--<p>Добавленные здесь тренинги будут размещены на странице <a href="{{ route('trainings.index') }}">"Обучение"</a></p>--}}
				                <ul id="category">
				                    @foreach($my_trainings as $training)
						                @include('partials._trainingsBlock')
					                @endforeach
				                </ul>
			                </div>
		                </div>
	                </div>
                </div>
            </div>
        </div>
    </div><!--  end room -->
@stop