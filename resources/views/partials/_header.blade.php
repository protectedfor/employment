<!-- Нав Бар -->
<div class="navbar">
    <nav class="navbar navbar-fixed-top size">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('home') }}"><img src="/logo.png" alt=""></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
	                @if($pageAdd = \App\Models\Page::where('slug', 'dobavit')->first())
                        {{--<li @if(Request::url() == route('page', $pageAdd->slug)) class="active" @endif> <a href="{{ route('page', $pageAdd->slug) }}">Добавить+</a></li>--}}
		                @ReplaceBlock('pages.partials._addItemBlock')
	                @endif
                    <li @if(Request::url() == route('vacancies.index')) class="active" @endif><a href="{{ route('vacancies.index') }}">Вакансии</a></li>
                    <li @if(Request::url() == route('resumes.index')) class="active" @endif><a href="{{ route('resumes.index') }}">Резюме</a></li>
	                <li @if(Request::url() == route('companies.index')) class="active" @endif><a href="{{ route('companies.index') }}">Компании</a></li>
	                <li @if(Request::url() == route('trainings.index')) class="active" @endif><a href="{{ route('trainings.index') }}">Обучение</a></li>
	                <li @if(Request::url() == route('articles.index')) class="active" @endif><a href="{{ route('articles.index') }}">Полезное</a></li>
	                @ReplaceBlock('auth.partials._authBlock')
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div>
<!-- Конец нав бара -->