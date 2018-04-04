@extends('layouts.app')
@section('content')

	<div class="companies">
	    <div class="container">
		    <div class="row">
		        <h1>Поиск по компаниям</h1>
			    <form action="{{ route('companies.index') }}" class="form-inline filter-form">
			        <div class="form-group">
			            <label for="exampleInputName2">Найдите по названию компаний</label>
				        <input name="query" value="{{ Request::get('query') }}" type="text" class="form-control filter-input" placeholder="Название компании">
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
			            <label class="label--checkbox">
			            <input value="1" name="with_vacancies" id="chexbox" type="checkbox" class="checkbox filter-select" @if(Request::get('with_vacancies') == 1) checked @endif>
			            Компании с открытыми вакансиями
			            </label>
			        </div>
				    {{--<button type="submit">Применить</button>--}}
		        </form>

		        <div class="rezums">
			        <div class="result">
				        <p>Всего <span class="total_entries">{{ $companies->total() }} {{ trans_choice('компания|компании|компаний', $companies->total()) }}</span></p>
			        </div>

			        <div class="filtered_container"></div>
{{--			        @if($companies->hasMorePages())--}}
				        <a class="show_more" data-type="companies" data-page="{{ $companies->currentPage() }}" href="">Показать еще</a>
			        {{--@endif--}}

		        </div>
		    </div>
	    </div>
	</div>

	{{--<div class="paginations">--}}
		{{--<div class="container">--}}
			{{--<div class="row">--}}
				{{--<nav>--}}
					{{--{!! $companies->appends([--}}
						{{--'query' => Request::get('query'),--}}
						{{--'city_id' => Request::get('city_id'),--}}
						{{--'scope_id' => Request::get('scope_id'),--}}
						{{--'with_vacancies' => Request::get('with_vacancies'),--}}
					{{--])->render() !!}--}}
				{{--</nav>--}}
			{{--</div>--}}
		{{--</div>--}}
	{{--</div>--}}
@endsection