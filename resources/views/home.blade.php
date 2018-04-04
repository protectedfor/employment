@extends('layouts.app')
@section('content')

    <div class="indexcategory">
        <div class="container">
            <div class="row">
                <!-- левый тул бар -->
                <div class="col-md-9 b-leading">
                    <h1>Горячие вакансии в Бишкеке и Кыргызстане</h1>
                    <ul>
                        @foreach($vacancies->filter(function ($item) {return $item['is_hot'] == true;}) as $vacancy)
		                    @include('partials._vacanciesBlock')
                        @endforeach
                    </ul>
                    <div class="seemore">
	                    <a href="{{ route('vacancies.index') }}">Посмотреть все вакансии ({{ \App\Models\Vacancies\Vacancy::moderated()->count() }})</a>
                    </div>
	                @include('partials._bannersBlock', ['position' => 'main_center', 'size' => '830_300', 'wrapperClass' => 'main_center_banner'])
	                <div class="indexjobs" style="background-image: url(img/jpg/banner2.jpg);">
		                <div class="container">
			                <div class="row">
				                <div class="col-md-7">
					                <h2>Ведущие работодатели</h2>
					                <ul>
						                @foreach($companies->filter(function ($item) {return $item['is_leading'] == true;}) as $company)
							                <li data-toggle="tooltip" title="{{ $company->title }}">
								                <div class="img-wrapper" >
									                <a href="{{ route('companies.show', $company->id) }}" >
										                @if($company->logo)
											                <img src="{{ route('imagecache', ['120_120', $company->logo]) }}" alt="">
										                @else
											                <img src="https://placehold.it/120x120" alt="">
										                @endif
									                </a>
								                </div>
							                </li>
						                @endforeach
					                </ul>
				                </div>
			                </div>
		                </div>
	                </div><!-- конец ведущих работодатели -->
                </div><!-- конец левого тул бар -->

	            <div class="col-md-3">
		            @include('partials._vacancies_by_scope')
		            @include('partials._vacancies_kg_by_companies')
		            <div class="seemore">
			            <a href="{{ route('companies.index') }}">Все компании ({{ $companies->count() }})</a>
		            </div>
	            </div><!-- конец правого тул бар -->
            </div>
        </div>
    </div> <!-- end indexcategory -->

    @if(count($trainings))
	    <div class="indextrenning">
	        <div class="container-fluid">
	            <div class="row">
	                <h2>Обучающие тренинги</h2>
	                <ul>
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
		                        <h3>{{ str_limit(strip_tags($training->title), 60) }}</h3>
		                        <span>Дата начала: {{ $training->start_date }}</span>
		                        {{--<span>{{ $training->schedule }}</span>--}}
		                        <p>{{ str_limit(strip_tags($training->description), 140) }}</p>
		                    </li>
						@endforeach
	                </ul>
	                <div class="seemore">
	                    <a href="{{ route('trainings.index') }}">
		                    Посмотреть все тренинги ({{ \App\Models\Training::moderated()->count() }})
	                    </a>
	                </div>
	            </div>
	        </div>
	    </div><!-- Конец обучающих трененгов -->
    @endif

    <div class="indexuseful">
        <div class="container">
            <div class="row">
                <h2>Полезное</h2>
                <ul>
                    @foreach($articles as $article)
                        <li>
                            <a href="{{ route('articles.show', $article->id) }}" target="_blank"></a>
                            <div class="img-wrapper">
                                <img src="{{ route('imagecache', ['260_170', $article->image]) }}" alt="">
                            </div>
                            <h3>{{ str_limit($article->title, 50) }}</h3>
                            <span>{{ $article->created_at->format('d.m.Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="seemore">
                    <a href="{{ route('articles.index') }}">
                        Посмотреть все публикации ({{ \App\Models\Article::all()->count() }})
                    </a>
                </div>
            </div>
        </div>
    </div><!-- конец полезного -->
@endsection