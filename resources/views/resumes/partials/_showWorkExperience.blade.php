<?php $slug = isset($lang) ? 'english_slug' : 'title' ?>

<div class="category">
	<h2>{{ $workExperience }} {{ $resume->totalWorkExperienceInRussian(@$lang) }}</h2>
	@foreach($resume->workExperiences->sortByDesc('exp_start_work') as $workExperience)
		<div class="subcategory">
			<div class="left">
				<div class="date">
					<h4>{{ $workExperience->exp_start_work->format('d.m.Y') }} -</h4>
					<h4>{{ $workExperience->exp_end_work ? $workExperience->exp_end_work->format('d.m.Y') : $tillNow }}</h4>
					<span>{{ $workExperience->workExperienceInRussian() }}</span>
				</div>
			</div>
			<div class="right">
				<div class="about">
					<h3>{{ $workExperience->position }}</h3>
					<p style="margin-bottom: 25px;"><b>{{ $workExperience->organization }}</b></p>
					@if($workExperience->jobField->title)<p><b>{{ $fieldOfActivity }}:</b> {{ $workExperience->jobField->{$slug} }}</p>@endif
					@if($workExperience->exp_org_site)<p><b>{{ $website }}:</b> {{ $workExperience->exp_org_site }}</p>@endif
					@if(!$workExperience->city->used_for)<p><b>{{ $placeOfWork }}:</b> {{ $workExperience->city->{$slug} }}</p>@endif
					@if($workExperience->exp_achievements)<p><b style="display: block; margin-bottom: {{ isset($pdf) ? '0' : '10px' }};">{{ $responsibilities }}:</b> {{ str_replace(['<br />', '<br/>'], '', $workExperience->exp_achievements) }}</p>@endif
					@if($workExperience->scope)<p>{{ $workExperience->scope }}</p>@endif
				</div>
			</div>
		</div> <!-- end subcategory -->
		@if(isset($pdf)) <div class="clearfix"></div> @endif
	@endforeach
</div> <!-- end category -->
<div class="clearfix"></div>