<?php $banner = $banners->filterFix('position.slug', '=', 'main_top')->first(); ?>
@if(isset($banners) && $banners->count() > 0 && $banner)
	<?php $banner->increment('views') ?>
	<div class="banner" style="background-image: url('{{ url($banner->setImage('1580_315', 'image')) }}')">
    <img src="{{ url($banner->setImage('1580_315', 'image')) }}" width="100%" class="user_mobile_banner" alt="">
@else
    <div class="banner" style="background-image: url(/img/jpg/banner.jpg);">
    <img src="/img/jpg/banner.jpg" width="100%" class="user_mobile_banner" alt="">
@endif
    <div class="container">
        <div class="row">
	        <a href="{{ isset($banner) && $banner->url ? $banner->url : route('home') }}" class="cover-url banner_link" data-id="{{ isset($banner) ? $banner->id : '' }}" target="_blank"></a>
        </div>
    </div>
</div>