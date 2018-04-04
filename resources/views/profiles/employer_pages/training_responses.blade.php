@extends('layouts.app_no_banner')
@include('profiles.partials._employer_assets')
@section('content')
    <div class="room">
        <div class="container">
            <div class="row">
                @include('profiles.partials._company_block')
                <div class="col-md-9">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="otklik">
                            <div class="title">
                                <div class="pull-left">
	                                <h2>Отклики на тренинги</h2>
                                </div>
                                {{--<div class="pull-right">--}}
                                    {{--<a href="">Входящие(12)</a>--}}
                                    {{--<a href="">Отклоненные (23)</a>--}}
                                {{--</div>--}}
                            </div>
                            @foreach($trainings as $training)
		                        <?php $responses = $training->responses ?>
                                @if(count($responses) > 0)
                                    <div class="section">
                                        <h3>{{ $training->title }}, {{ $training->coordinator }}
                                            <span>({{ count($responses) }})</span></h3>
                                        <ul>
                                            @foreach($responses as $response)
		                                        <?php $profile = $response->user->profile ?>
		                                        @include('partials._trainingsBlock')
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        {{--{!! $vacancies->render() !!}--}}
                    </div>
                </div>
            </div>
        </div>
    </div><!--  end room -->
@stop