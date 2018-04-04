@if($company->logo)
	<div class="img-wrapper">
		<img src="{{ route('imagecache', ['170_170', $company->logo]) }}" alt="">
	</div>
@endif
@if($company->title)
	<h2><a href="{{ route('companies.show', $company->id) }}" target="_blank">{{ $company->title }} </a></h2>
@endif
@if($company->address)
	<span>{{ $address }}</span>
	<p>{{ $company->address }}</p>
@endif
{{--<a href="" data-toggle="modal" data-target="#myModal" id="gmaps">Показать на карте</a>--}}
@if($company->fio && $company->show_fio)
	<span>{{ $contactPerson }}</span>
	<p>{{ $company->fio }}</p>
@endif
@if($company->phone && $company->show_phone)
	<span>{{ $phone }}</span>
	<p>{{ $company->phone }}</p>
@endif
@if($company->site && $company->show_site)
	<span>Web</span>
	<?php $suffix = explode(':', $company->site)[0] ?>
	<p><a href="@if($suffix && ($suffix == 'http' || $suffix == 'https')){{ $company->site }}@else http://{{ $company->site }}@endif" target="_blank">{{ $company->site }}</a></p>
@endif