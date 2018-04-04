@extends('layouts.app')
@section('content')
    <div class="onestation">
	    <div class="container">
		    <div class="row">
			    <div class="backarrow">
				    <img src="/img/png/arrwoback.png" alt="">
				    <a href="{{ route('articles.index') }}">Статьи</a>
			    </div> <!-- end backarrow -->
			    <div class="col-md-9">
				    <div class="titleblog">
					    <h1>{{ $article->title }}</h1>
					    <span>{{ $article->created_at->format('d.m.Y H:i:s') }}</span>
					    <img src="{{ route('imagecache', ['articles_show', $article->image]) }}" alt="">
					    <div class="page">
					    	{!! $article->body !!}
					    </div>
				    </div>

				    <div class="share">
					    @if($article->url)
						    <span>Источник: <a href="{{ $article->url }}"  target="_blank">{{ str_limit($article->source, 60) }}</a></span>
						@else
						    <span>Источник: {{ str_limit($article->source, 60) }}</span>
					    @endif
					    <div class="pull-right m-pull-right">
					        @include('partials._shareSocials')
				        </div>
				    </div>
			    </div><!-- end col-md-9 -->
			    <div class="col-md-3">
				    @include('partials._vacancies_by_scope')
				    @include('partials._vacancies_kg_by_companies')
				    <div class="seemore">
					    <a href="{{ route('companies.index') }}">Все компании ({{ $companies->count() }})</a>
				    </div>
				    @include('partials._bannersBlock', ['position' => 'articles_right', 'size' => '260_500', 'wrapperClass' => 'articles_right_banner'])
			    </div><!-- конец правого тул бар -->
		    </div>
	    </div>
    </div><!-- end indexcategory -->

    <div class="similar_articles">
	    <div class="container">
		    <div class="row">
			    <h2>Похожие статьи</h2>
			    <ul class="station">
				    @foreach(\App\Models\Article::orderByRaw("RAND()")->take(8)->get() as $article)
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
		    </div>
	    </div>
    </div>
@stop