<!-- футер -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <h2>Employment.kg</h2>
                <ul>
                    @foreach($customPages->filter(function ($item) {return $item['position'] == 1 && $item['active'] == 1;})->sortBy('order') as $page)
                        <li><a href="{{ $page->url ? url($page->url) : route('page', $page->slug) }}">{{ $page->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3" id="col2">
                <ul>
                    @foreach($customPages->filter(function ($item) {return $item['position'] == 2 && $item['active'] == 1;})->sortBy('order') as $page)
                        <li><a href="{{ $page->url ? url($page->url) : route('page', $page->slug) }}">{{ $page->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
	            @if($contact->address || $contact->email || array_get($contact->phone, 0) != '')
	                <h2>Контакты</h2>
	                <ul class="info">
		                @if($contact->address)<li>{{ $contact->address }}</li>@endif
		                @if($contact->email)<li><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></li>@endif
		                @if(array_get($contact->phone, 0) != '')
		                    @foreach($contact->phone as $phone)
		                        <li>{{ $phone }}</li>
		                    @endforeach
						@endif
	                </ul>
	            @endif
            </div>
            <div class="col-md-offset-1 col-md-3">
                <h2>Будьте на связи</h2>
                <span>Мы в соц сетях:</span>
                <ul class="social">
                    <li><a @if($contact->facebook_url) href="{{ $contact->facebook_url }}" @endif target="_blank"><img src="/img/png/facebook.png" alt=""></a></li>
                    <li><a @if($contact->twitter_url) href="{{ $contact->twitter_url }}" @endif target="_blank"><img src="/img/png/tweet.png" alt=""></a></li>
                    <li><a @if($contact->instagram_url) href="{{ $contact->instagram_url }}" @endif target="_blank"><img src="/img/png/insta.png" alt=""></a></li>
                </ul>
                <form action="{{ route('subscribe')}}">
                    <label for="">Подпишитесь на рассылку, чтобы не пропустить интересные материалы и важные новости</label>
                    <input class="form-control" name="mailing_email" type="email" required value="@if(Auth::check()){{ Auth::user()->email }}@endif">
                    <button type="submit" class="btn"></button>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="bottomline">
                <div class="pull-left">
                    @if(!env('APP_DEBUG'))
                        @if($widgets->get('snipets'))
                            {!! $widgets->get('snipets')->value !!}
                        @endif
                    @endif
                    <span> © {{ \Carbon\Carbon::now()->format('Y') }} EMPLOYMENT.KG — Все права защищены</span>
                </div>
                <div class="pull-right">
                    {{--<a href=""><img src="/img/png/apps.png" alt=""></a>--}}
                    {{--<a href=""><img src="/img/png/goggplay.png" alt=""></a>--}}
                    <a style="color: #949a9d;" href="http://googstudio.com" target="blank">Разработано Googstudio</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('libs/jquery-bbq/jquery.ba-bbq.min.js') }}"></script>
<script src="{{ asset('libs/alertifyjs/js/alertify.min.js') }}"></script>
<script src="{{ asset('js/custom_functions.js') }}"></script>
@yield('js-assets')
<script src="{{ asset('js/bootstrapdate.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
