@if($resume->extraInstitutions->count() && $resume->extraInstitutions->first()->extra_inst_title)
	<div class="category2 category2e">
		<h2>{{ $extraEducation }}</h2>
		@foreach($resume->extraInstitutions->sortByDesc('extra_inst_date') as $institution)
			<div class="subcategory">
				<div class="left">
					<div class="date">
						<h4>{{ $institution->extra_inst_date }}</h4>
					</div>
				</div>
				<div class="right">
					<div class="about">
						<h3>{{ $institution->extra_inst_title }}</h3>
						<p><b>{{ $institution->extra_inst_organizer }}</b></p>
						<p>{{ $institution->extra_inst_location }}</p>
					</div>
				</div>
			</div> <!-- end subcategory -->
			@if(isset($pdf)) <div class="clearfix"></div> @endif
		@endforeach
	</div> <!-- end category -->
@endif
<div class="clearfix"></div>