<?php $slug = isset($lang) ? 'english_slug' : 'title' ?>

<div class="img-wrapper">
	@if($resume->photo)
		<img src="{{ route('imagecache', ['170_220', $resume->photo]) }}" alt="">
	@else
		<img src="https://placehold.it/170x220" alt="">
	@endif
</div>
<div class="table">
	<h1>{{ $resume->career_objective }}</h1>
	@if($accessToContacts)
		<p>{{ $resume->sname }} {{ $resume->name }} {{ $resume->mname }}</p>
	@else
		<p>{{ $resume->name }}</p>
	@endif
	<table class="rwd-table">
		<tr>
			<td>{{ $published }}:</td>
			<td>{{ $resume->created_at->format('d.m.Y H:i:s') }}</td>
		</tr>
		@if($accessToContacts)
			<tr>
				<td>{{ $phone }}:</td>
				<td>{{ $resume->phone }}</td>
			</tr>
			<tr>
				<td>E-mail:</td>
				<td>{{ $resume->user->email }}</td>
			</tr>
		@endif
		<tr>
			<td>{{ $age }}:</td>
			<td>{{ \Carbon\Carbon::instance($resume->date_of_birth)->diffInYears()}}</td>
		</tr>
		@if (isset($resume->city))
			<tr>
				<td>{{ $city }}:</td>
				<td>{{ $resume->city->{$slug} }}</td>
			</tr>
		@endif
		@if (isset($resume->busyness))
			<tr>
				<td>{{ $business }}:</td>
				<td>{{ $resume->busyness->{$slug} }}</td>
			</tr>
		@endif
		@if (isset($resume->resumeField))
			<tr>
				<td>{{ $fieldOfActivity }}:</td>
				<td>{{ $resume->resumeField->{$slug} }}</td>
			</tr>
		@endif
		@if (isset($resume->salary) && $resume->salary)
			<tr>
				<td>{{ $salary }}:</td>
				<td>{{ $resume->salary }} {{ $resume->currency->{$slug} }}</td>
			</tr>
		@endif
	</table>
</div> <!-- end div table -->