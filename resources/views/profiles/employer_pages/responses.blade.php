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
	                                <h2>{{ $type == 'responses' ? 'Отклики на вакансии' : 'Мои предложения вакансий' }}</h2>
                                </div>
                            </div>
                            @foreach($vacancies as $vacancy)
		                        <?php $type == 'responses' ? $responses = $vacancy->responses->sortByDesc('created_at') : $responses = $vacancy->offers->sortByDesc('created_at') ?>
                                @if(count($responses) > 0)
                                    <div class="section">
                                        <h3>{{ $vacancy->position }}
                                            @if($vacancy->vacancyField)
                                                , {{ $vacancy->vacancyField->title }}
                                            @endif
                                            <span>({{ count($responses) }})</span></h3>
                                        <ul>
                                            @foreach($responses->load('resume') as $response)
		                                        <?php $resume = $response->resume ?>
		                                        @include('partials._resumesBlock')
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