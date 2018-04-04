@extends('layouts.app')
@section('content')
    <div class="rezumesmall">
        <div class="container">
            <div class="row">
                <h1>Поиск резюме</h1>
                <form action="{{ route('resumes.index') }}" class="form-inline filter-form">
                    <div class="form-group">
                        <label for="">Найдите по ключевому слову</label>
                        <input name="query" value="{{ Request::get('query') }}" type="text" class="form-control filter-input" placeholder="Ключевое слово">
                    </div>
                    <div class="form-group">
                        <label for="">Найдите в определенном городе</label>
	                    {!! Form::select('city_id', $cities->lists('title', 'id'), Request::get('city_id'), ['class' => 'form-control filter-select', 'placeholder' => 'Все города']) !!}
                    </div>
                    <div class="form-group">
                        <label for="">Найдите по сфере деятельности</label>
                        {!! Form::select('scope_id', $scopes->lists('title', 'id'), Request::get('scope_id'), ['class' => 'form-control filter-select', 'placeholder' => 'Все сферы деятельности']) !!}

                    </div>
                    <div class="form-group" id="olds">
                        <label for="">Найдите по возрасту:</label>
                        {!! Form::select('year_from', array_combine(range(15, 80), range(15, 80)), Request::get('year_from'), ['class' => 'form-control filter-select', 'placeholder' => 'От']) !!}
                        {!! Form::select('year_to', array_combine(range(15, 80), range(15, 80)), Request::get('year_to'), ['class' => 'form-control filter-select', 'placeholder' => 'До']) !!}
                    </div>
                </form>

                <div class="rezums">
                    <div class="result">
	                    <p>Всего <span class="total_entries">{{ $resumes->total() }} {{ trans_choice('резюме|резюме|резюме', $resumes->total()) }}</span></p>
                    </div>

	                <div class="filtered_container"></div>
	                {{--@if($resumes->hasMorePages())--}}
		                <a class="show_more" data-type="resumes" data-page="{{ $resumes->currentPage() }}" href="">Показать еще</a>
	                {{--@endif--}}

                </div>
            </div>
        </div>
    </div>
    {{--<div class="paginations">--}}
	    {{--<div class="container">--}}
		    {{--<div class="row">--}}
			    {{--<nav>--}}
				    {{--{!! $resumes->appends([--}}
						{{--'query' => Request::get('query'),--}}
						{{--'city_id' => Request::get('city_id'),--}}
						{{--'scope_id' => Request::get('scope_id'),--}}
						{{--'year_from' => Request::get('year_from'),--}}
						{{--'year_to' => Request::get('year_to'),--}}
					{{--])->render() !!}--}}
			    {{--</nav>--}}
		    {{--</div>--}}
	    {{--</div>--}}
    {{--</div>--}}
@endsection