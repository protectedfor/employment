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
                        <div role="tabpanel" class="tab-pane fade in active" id="vakansia">

                            <div class="title">
                                <div class="pull-left">
                                    <h2>Мои вакансии</h2>
                                </div>
                                <div class="pull-right">
									@if(Request::url() == route('employers.profile')) <p>На сайте <span>({{ $my_vacancies->count() }})</span></p> @else <a href="{{ route('employers.profile') }}">На сайте ({{ $my_vacancies->count() }})</a> @endif
									@if(Request::url() == route('employers.profile.checking')) <p>На проверке <span>({{ $checking_vacancies->count() }})</span></p> @else <a href="{{ route('employers.profile.checking') }}">На проверке ({{ $checking_vacancies->count() }})</a> @endif
									@if(Request::url() == route('employers.profile.drafts')) <p>Черновики <span>({{ $drafts->count() }})</span></p> @else <a href="{{ route('employers.profile.drafts') }}">Черновики ({{ $drafts->count() }})</a> @endif
                                    {{--<a href="">В архиве (23)</a>--}}
                                </div>
                            </div>

                            <div class="categorys">
	                            <a href="{{ route('vacancies.create') }}" class="addmore">+ Добавить вакансию</a>
                                <ul id="category">
	                                @if(Request::url() == route('employers.profile.checking'))
										<?php $vacancies = $checking_vacancies ?>
	                                @elseif(Request::url() == route('employers.profile.drafts'))
										<?php $vacancies = $drafts ?>
	                                @else
										<?php $vacancies = $my_vacancies ?>
	                                @endif

                                    @foreach($vacancies as $vacancy)
		                                @include('partials._vacanciesBlock')
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