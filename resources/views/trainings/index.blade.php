@extends('layouts.app')
@section('content')
    <div class="rezumesmall">
        <div class="container">
            <div class="row">

	            <div class="teacher-small">
		            <div class="container">
			            <div class="row">
							@if(Request::all())
					            <div class="backarrow">
						            <img src="/img/png/arrwoback.png" alt="">
						            <a href="{{ route('trainings.index') }}">Назад</a>
					            </div> <!-- end backarrow -->
							@endif
				            <div class="col-md-3">
								@include('partials._educationBlock')
								@include('partials._bannersBlock', ['position' => 'trainings_left', 'size' => '260_500', 'wrapperClass' => 'trainings_left_banner'])
				            </div>
				            <div class="col-md-9">
					            <ul class="see-more">
									@foreach($trainings as $training)
							            <li>
								            <a href="{{ route('trainings.show', $training->id) }}" target="_blank"></a>
								            <div class="img-wrapper">
									            @if($training->photo)
										            <img src="{{ route('imagecache', ['260_170', $training->photo]) }}" alt="">
									            @else
										            <img src="https://placehold.it/260x170" alt="">
									            @endif
								            </div>
								            <p>{{ str_limit(strip_tags($training->title), 60) }}</p>
								            <span>Дата начала: {{ $training->start_date }}</span>
{{--								            <span>{{ $training->created_at->format('Y-m-d') }}</span>--}}
							            </li>
									@endforeach
					            </ul>
				            </div>
				            <div class="clearfix"></div>
					            <div class="paginations">
						            <div class="container">
							            <div class="row">
								            <nav>
									            {!! $trainings->appends([
													/*'query' => Request::get('query'),*/
													'location' => Request::get('location'),
													'category_id' => Request::get('category_id'),
													/*'busyness_id' => Request::get('busyness_id'),*/
												])->render() !!}
								            </nav>
							            </div>
						            </div>
					            </div>
			            </div>
		            </div>
	            </div>
            </div>
        </div>
    </div>
@endsection