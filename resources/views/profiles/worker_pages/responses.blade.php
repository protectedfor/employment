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
                                    <h2>{{ $type == 'offers' ? 'Мои отклики на вакансии' : 'Предложения вакансий' }}</h2>
                                </div>
                                {{--<div class="pull-right">--}}
                                    {{--<a href="">Входящие(12)</a>--}}
                                    {{--<a href="">Отклоненные (23)</a>--}}
                                {{--</div>--}}
                            </div>
                            @foreach($resumes as $resume)
		                        <?php $type == 'responses' ? $responses = $resume->responses->sortByDesc('created_at') : $responses = $resume->offers->sortByDesc('created_at') ?>
	                            @if(count($responses) > 0)
                                    <div class="section">
                                        <h3>{{ $resume->career_objective }}
                                            @if($resume->scope)
                                                , {{ $resume->scope->title }}
                                            @endif
	                                        @if($resume->busyness)
		                                        , занятость: {{ $resume->busyness->title }}
	                                        @endif
                                            <span>({{ $responses->filter(function ($item) {return $item['vacancy']['expires_at'] > \Carbon\Carbon::now() ||
                                                      $item['vacancy']['created_at'] > \Carbon\Carbon::now()->subDays(30);})->count() }})</span>
                                        </h3>
                                        <ul>
                                            @foreach($responses as $response)
												<?php $vacancy = $response->vacancy ?>
	                                            @if($vacancy->expires_at ? $vacancy->expires_at > \Carbon\Carbon::now() : $vacancy->created_at > \Carbon\Carbon::now()->subDays(30))
		                                            @include('partials._vacanciesBlock')
												@endif
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