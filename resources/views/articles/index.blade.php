@extends('layouts.app')
@section('content')
    <div class="articles">
        <div class="container">
            <div class="row">
                <div class="backarrow"></div> <!-- end backarrow -->
                <!-- левый тул бар -->
                <div class="col-md-9">
                    <h1>Полезное</h1>
                    <ul class="acticle">
                        @foreach($articles as $article)
                            <li>
                                <a href="{{ route('articles.show', $article->id) }}" target="_blank"></a>
                                <div class="img-wrapper">
                                    <img src="{{ route('imagecache', ['260_170', $article->image]) }}" alt="">
                                </div>
                                <h2>{{ str_limit($article->title) }}</h2>
                                <span>{{ $article->created_at->format('d.m.Y H:i') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="paginations">
                        <nav>
                            {!! $articles->render() !!}
                        </nav>
                    </div>
                </div>
                <div class="col-md-3">
                    @include('partials._vacancies_by_scope')
                    @include('partials._vacancies_kg_by_companies')
	                <div class="seemore">
		                <a href="{{ route('companies.index') }}">Все компании ({{ $companies->count() }})</a>
                    </div>
                    @include('partials._bannersBlock', ['position' => 'articles_right', 'size' => '260_500', 'wrapperClass' => 'articles_right_banner'])
                </div>
                <!-- конец правого тул бар -->
            </div>
        </div>
    </div> <!-- end indexcategory -->
@stop