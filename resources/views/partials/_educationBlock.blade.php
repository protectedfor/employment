<h1>Обучение</h1>
	<h2>За рубежом</h2>
	<ul>
		@foreach($fields as $field)
			@if($field->trainingFilter('1')->count() > 0)
				<li><a href="{{ route('trainings.index', ['location' => true, 'category_id' => $field->id]) }}"><span class="pull-right">{{ $field->trainingFilter('1')->count() }}</span>{{ $field->title }}</a></li>
			@endif
		@endforeach
	</ul>
	<h2>В Кыргызстане</h2>
	<ul>
		@foreach($fields as $field)
			@if($field->trainingFilter('0')->count() > 0)
				<li><a href="{{ route('trainings.index', ['location' => false, 'category_id' => $field->id]) }}"><span class="pull-right">{{ $field->trainingFilter('0')->count() }}</span>{{ $field->title }}</a></li>
			@endif
		@endforeach
	</ul>
