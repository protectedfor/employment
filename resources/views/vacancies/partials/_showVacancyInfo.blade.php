<?php $slug = isset($lang) && $lang == 2 ? 'english_slug' : 'title' ?>

<h1>{{ $vacancy->position }}</h1>
<table class="rwd-table">
	<tr>
		<td>{{ $published }}:</td>
		<td>{{ $vacancy->created_at->format('d.m.Y H:i:s') }}</td>
	</tr>
	<tr>
		<td>{{ $deadline }}:</td>
		@if($vacancy->expires_at == null)
			<td id="red">{{ $vacancy->created_at->addDays(30)->format('d.m.Y H:i:s') }}
				<span>@if(\Carbon\Carbon::now() > $vacancy->created_at->addDays(30)) {{ $expired }} <?php $expired_vacancy = true ?> @else {{ $left }} {{ \Carbon\Carbon::now()->diffInDays($vacancy->created_at->copy()->addDays(30)) }} {{ $days }} @endif</span>
			</td>
		@else
			<td id="red">{{ $vacancy->expires_at->addDay()->subSecond()->format('d.m.Y H:i:s') }}
				<span>@if(\Carbon\Carbon::now() > $vacancy->expires_at->addDay()) {{ $expired }}<?php $expired_vacancy = true ?> @else {{ $left }} {{ \Carbon\Carbon::now()->diffInDays($vacancy->expires_at->addDay()) }} {{ $days }} @endif</span>
			</td>
		@endif
	</tr>
	<tr>
		<td>{{ $salary }}:</td>
		@if($vacancy->wages_from || $vacancy->wages_to)
			<td>
				@if($vacancy->wages_from)
					от {{ $vacancy->wages_from }}@if($vacancy->currency->title != '$' && !$vacancy->wages_to) @endif{{ $vacancy->salaryCurrency && !$vacancy->wages_to ? $vacancy->salaryCurrency->title : '' }}
				@endif
				@if($vacancy->wages_to)
					до {{ $vacancy->wages_to }}@if($vacancy->currency->title != '$') @endif{{ $vacancy->salaryCurrency ? $vacancy->salaryCurrency->title : '' }}
				@endif
			</td>
		@else
			<td>{{ $perInterview }}</td>
		@endif
	</tr>
	@if($vacancy->experience)
		<tr>
			<td>{{ $workExperience }}:</td>
			<td>{{ $vacancy->experience }}</td>
		</tr>
	@endif
	{{--@if($vacancy->place_of_work)--}}
	{{--<tr>--}}
	{{--<td>Место работы:</td>--}}
	{{--<td>{{ $vacancy->place_of_work }}</td>--}}
	{{--</tr>--}}
	{{--@endif--}}
	@if($vacancy->city_id)
		<tr>
			<td>{{ $city }}:</td>
			<td>{{ $vacancy->city->{$slug} }}</td>
		</tr>
	@endif
	@if($vacancy->busyness)
		<tr>
			<td>{{ $business }}:</td>
			<td>{{ $vacancy->busyness->{$slug} }}</td>
		</tr>
	@endif
	@if($vacancy->work_graphite)
		<tr>
			<td>{{ $schedule }}:</td>
			<td>{{ $vacancy->work_graphite }}</td>
		</tr>
	@endif
</table>