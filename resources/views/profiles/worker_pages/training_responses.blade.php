@extends('layouts.app_no_banner')
@include('profiles.partials._worker_assets')
@section('content')
    <div class="room">
        <div class="container">
            <div class="row">
	            @include('profiles.partials._worker_profile_block')
                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="otklik">
                            <div class="title">
                                <div class="pull-left">
                                    <h2>Мои отклики на тренинги</h2>
                                </div>
                                {{--<div class="pull-right">--}}
                                    {{--<a href="">Входящие(12)</a>--}}
                                    {{--<a href="">Отклоненные (23)</a>--}}
                                {{--</div>--}}
                            </div>
	                        <div class="section">
		                        <ul>
		                            @foreach($trainings as $training)
				                        @include('partials._trainingsBlock')
		                            @endforeach
		                        </ul>
	                        </div>
                        </div>
                        {{--{!! $vacancies->render() !!}--}}
                    </div>
                </div>
            </div>
        </div>
    </div><!--  end room -->
@stop