<div class="col-md-3 imageUpload" data-target="{{ route('ajax.uploadImage', ['section' => 'company_logo', 'company_id' => Auth::user()->company->id]) }}" data-token="{{ csrf_token() }}">
    <div class="img-wrapper thumbnail">
        <img class="has-value img-responsive {{ Auth::user()->company->logo ?: 'hidden' }}" src="{{ Auth::user()->company->logo ? route('imagecache', ['158_158', Auth::user()->company->logo]) : '' }}" id="img" alt="">
        <img class="no-value img-responsive {{ Auth::user()->company->logo ? 'hidden' : '' }}" src="https://placehold.it/158x158" alt="">
    </div>
    @if(Auth::user()->company->title)
        <h2>{{ Auth::user()->company->title }}</h2>
    @endif
    <a style="cursor:pointer;" class="imageBrowse">Сменить логотип</a>

    <div class="errors">
        {{--<p class="help-block">Invalid image type</p>--}}
    </div>
    <input name="photo" value="" type="hidden" class="imageValue" id="photo" accept="image/*;capture=camera" capture="camera"/>

    <p>Ваш лицевой счет: <span>№ {{ Auth::user()->personal_bill }}</span></p>
    <p>Ваш баланс <span>{{ Auth::user()->balance }} сом</span></p>
    <a href="{{ route('employers.profile.fill_up_balance') }}" class="balans">пополнить баланс</a>

    <!-- Nav tabs -->
    @include('profiles.partials._employer_tabs')
    <div class="discript-cab">
	   <p class="text-center">Вы проводите курсы/тренинги и хотите размещать их на нашем сайте (в разделе "Обучение")?</p>
    </div>
	<a href="{{ route('trainings.create') }}" class="balans">
        Разместить курс / тренинг
    </a>
	<a href="{{ route('trainings.profile') }}" class="my-training">
        <img src="/img/png/training.png" alt="">Мои тренинги
    </a>
    <div class="bgcover"></div>
</div> <!-- end col-md-3 -->