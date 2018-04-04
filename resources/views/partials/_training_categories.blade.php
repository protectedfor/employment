@if (isset($loc))
	@if ($loc)
		{!! Form::select('category_id', $categoriesExternal->lists('title', 'id'), isset($training) ? $training->category_id : '', ['class' => 'form-control training_categories', 'placeholder' => 'Выберите категорию']) !!}
	@else
		{!! Form::select('category_id', $categoriesInternal->lists('title', 'id'), isset($training) ? $training->category_id : '', ['class' => 'form-control training_categories', 'placeholder' => 'Выберите категорию']) !!}
	@endif
@else
	{!! Form::select('category_id', $categories->lists('title', 'id'), old('category_id'), ['class' => 'form-control training_categories', 'placeholder' => 'Выберите категорию']) !!}
@endif