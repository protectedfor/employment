<?php
	$banner = $banners->filterFix('position.slug', '=', $position)->first();
	if($banner)
		$banner->increment('views');
?>

@if($banner)
	@if(Request::url() == route('ajax.vacancies')) <li style="padding: 0"> @endif
		<div class="{{ $wrapperClass }}">
			<a href="{{ isset($banner) && $banner->url ? $banner->url : route('home') }}" class="banner_link" data-id="{{ $banner->id }}" target="_blank">
				@if($banner->iframe)
					<iframe class="bnr-type-banner-iframe" src="{{ $banner->iframe }}"></iframe>
				@else
					@TagBlock([$type = "img", $var = $banner, $src = "image", $mod = 'fit', $p = "class=\"banner-image\""])
				@endif
			</a>
		</div>
	@if(Request::url() == route('ajax.vacancies')) </li><li style="display: none"></li> @endif
@endif