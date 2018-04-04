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
                        <div role="tabpanel" class="tab-pane fade in active" id="vakansia">

                            <div class="title">
                                <div class="pull-left">
                                    <h2>Мои резюме</h2>
                                </div>
                                <div class="pull-right">
	                                {{--@if(Request::url() != route('workers.profile.checking'))--}}
		                                {{--<p>На сайте <span>({{ $my_resumes->count() }})</span></p>--}}
		                                {{--<a href="{{ route('workers.profile.checking') }}">На проверке ({{ $checking_resumes->count() }})</a>--}}
	                                {{--@else--}}
		                                {{--<a href="{{ route('workers.profile') }}">На сайте ({{ $my_resumes->count() }})</a>--}}
		                                {{--<p>На проверке <span>({{ $checking_resumes->count() }})</span></p>--}}
	                                {{--@endif--}}
		                                @if(Request::url() == route('workers.profile')) <p>На сайте <span>({{ $my_resumes->count() }})</span></p> @else <a href="{{ route('workers.profile') }}">На сайте ({{ $my_resumes->count() }})</a> @endif
		                                @if(Request::url() == route('workers.profile.checking')) <p>На проверке <span>({{ $checking_resumes->count() }})</span></p> @else <a href="{{ route('workers.profile.checking') }}">На проверке ({{ $checking_resumes->count() }})</a> @endif
		                                @if(Request::url() == route('workers.profile.drafts')) <p>Черновики <span>({{ $drafts->count() }})</span></p> @else <a href="{{ route('workers.profile.drafts') }}">Черновики ({{ $drafts->count() }})</a> @endif
                                    {{--<a href="">В архиве (23)</a>--}}
                                </div>
                            </div>

                            <div class="categorys">
	                            <a href="{{ route('resumes.create') }}" class="addmore">+ Добавить резюме</a>
                                <ul id="category" class="-m">
	                                @if(Request::url() == route('workers.profile.checking'))
		                                <?php $resumes = $checking_resumes ?>
	                                @elseif(Request::url() == route('workers.profile.drafts'))
		                                <?php $resumes = $drafts ?>
	                                @else
		                                <?php $resumes = $my_resumes ?>
	                                @endif

                                    @foreach($resumes as $key =>$resume)
		                                @include('partials._resumesBlock')
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