@extends('layouts.app')
@section('content')
    <div class="rezumesmall">
        <div class="container">
            <div class="row">
                <h1>Поиск вакансий</h1>
                <form action="{{ route('vacancies.index') }}" class="form-inline filter-form">
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
                    <div class="form-group">
                        <label for="">Найдите по виду занятости</label>
                        {!! Form::select('busyness_id', $busynesses->lists('title', 'id'), Request::get('busyness_id'), ['class' => 'form-control filter-select', 'placeholder' => 'Все виды занятости']) !!}
                    </div>
                </form>

                <div class="rezums vacans">
                    <div class="result">
                        <p>Всего <span class="total_entries">{{ $vacancies->total() }} {{ trans_choice('вакансия|вакансии|вакансий', $vacancies->total()) }}</span></p>
                    </div>

                    <div class="filtered_container"></div>
{{--                    @if($vacancies->hasMorePages())--}}
                        <a class="show_more" data-type="vacancies" data-page="{{ $vacancies->currentPage() }}" href="">Показать еще</a>
                    {{--@endif--}}

                </div>
            </div>
        </div>
    </div>
    {{--<div class="paginations">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<nav>--}}
                    {{--{!! $vacancies->appends([--}}
                        {{--'query' => Request::get('query'),--}}
                        {{--'city_id' => Request::get('city_id'),--}}
                        {{--'scope_id' => Request::get('scope_id'),--}}
                        {{--'busyness_id' => Request::get('busyness_id'),--}}
                        {{--'user_id' => Request::get('user_id'),--}}
                    {{--])->render() !!}--}}
                {{--</nav>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection