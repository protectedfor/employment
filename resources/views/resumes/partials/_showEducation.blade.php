<?php $slug = isset($lang) ? 'english_slug' : 'title' ?>

<div class="category2">
	<h2>{{ $education }}</h2>
	@foreach($resume->institutions->sortByDesc('year_of_ending') as $institution)
		<div class="subcategory">
			<div class="left">
				<div class="date">
					<h4>{{ $institution->year_of_ending }}</h4>
				</div>
			</div>
			<div class="right">
				<div class="about">
					<h3>{{ $institution->specialty }}</h3>
					<p><b>{{ $institution->institution }}</b></p>
					<p><b>{{ $faculty }}:</b> {{ $institution->department }}</p>
					<p><b>{{ $levelOfEducation }}:</b> {{ $institution->education->{$slug} }}</p>
				</div>
			</div>
		</div> <!-- end subcategory -->
		@if(isset($pdf)) <div class="clearfix"></div> @endif
	@endforeach
</div> <!-- end category -->
<div class="clearfix"></div>