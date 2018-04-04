<div class="col-md-3 imageUpload" data-target="{{ route('ajax.uploadImage', ['section' => 'worker_profile_block', 'profile_id' => Auth::user()->profile->id]) }}" data-token="{{ csrf_token() }}">
    <div class="img-wrapper thumbnail">
        <img class="has-value img-responsive {{ Auth::user()->profile->logo ?: 'hidden' }}" src="{{ Auth::user()->profile->logo ? route('imagecache', ['158_158', Auth::user()->profile->logo]) : '' }}" id="img" alt="">
        <img class="no-value img-responsive {{ Auth::user()->profile->logo ? 'hidden' : '' }}" src="https://placehold.it/158x158" alt="">
    </div>
    <a style="cursor:pointer;" class="imageBrowse">Сменить фото</a>

    <div class="errors">
        {{--<p class="help-block">Invalid image type</p>--}}
    </div>
    <input name="photo" value="" type="hidden" class="imageValue" id="photo" accept="image/*;capture=camera" capture="camera"/>
	<p>Ваш лицевой счет: <span>№ {{ Auth::user()->personal_bill }}</span></p>
    {{--<p>Ваш баланс <span>{{ Auth::user()->balance }} сом</span></p>--}}
    {{--<a href="" class="balans">пополнить баланс</a>--}}

    <!-- Nav tabs -->
    @include('profiles.partials._workers_tabs')
    <div class="bgcover"></div>
</div> <!-- end col-md-3 -->