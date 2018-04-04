<div class="iformation">
	<h3>{{ $info }}</h3>
	@if($vacancy->overview)
		<h4 style="border-top: none; padding-top: 0">{{ $commonInfo }}</h4>
		{!! $vacancy->overview !!}
	@endif
	@if($vacancy->qualification_requirements)
		<h4>{{ $requirements }}</h4>
		{!! $vacancy->qualification_requirements !!}
	@endif
	@if($vacancy->duties)
		<h4>{{ $duties }}</h4>
		{!! $vacancy->duties !!}
	@endif
	@if($vacancy->conditions)
		<h4>{{ $conditions }}</h4>
		{!! $vacancy->conditions !!}
	@endif
</div> <!-- end iformation -->
<div class="aboutcomp">
	<h2>{{ $aboutCompany }}</h2>
	<p>{!! $company->about_company !!}</p>
</div>
<div class="clearfix"></div>