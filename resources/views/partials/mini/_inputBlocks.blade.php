<?php
	$filteredArr = \App\Models\Vars::getFilteredVars(get_defined_vars(), 'b_');
	foreach ($filteredArr  as $k => $f) {
		$k = str_replace(["b_", "_"], '', $k);
		if(is_array($f))
			${$k} = array_get($f, $b_item);
		else
			${$k} = $f;
	}
	$clearedItem = str_replace(['[', "]"], '', "{$item}");
	$errorItem = isset($errorKey) ? "{$clearedItem}.{$errorKey}" : $item;
	$hasError = $errors->has($errorItem) ? "has-error" : '';
	$formGroup = $type != 'checkbox' && $type != 'radio' ? "form-group" : "";
	$defaultClassForDiv = "class=\"{$formGroup} {$hasError}\"";
	$defaultClass = $type == 'checkbox' ? 'checkbox ' : ($type == 'radio' ? 'radio ' : ($type == 'multiSelect' ? 'form-control checkedItems ' : 'form-control '));
	$p = $type == 'multiSelect' ? @$p . ' multiple ' : @$p . " placeholder=\"Введите {$label}\" ";
	$p = $p ? $p . ' ' . "class={$defaultClass}" : "class={$defaultClass}";
?>

@if(@$dp != 'no-div')
	<div {!! @$dp ? $dp . ' ' . $defaultClassForDiv : $defaultClassForDiv !!}>
@endif
	@if(isset($label) && $type != 'checkbox' && $type != 'radio')
		<label for="{{ $item }}" {!! @$lp !!}>{{ $label }}:</label>
	@endif
	@if(@$sdp)
		<div {!! $sdp !!}>
	@endif

		@if($type === 'input')
			<input type="text" name="{{ $item }}" value="{{ old($item) ? old($item) : (isset($var) ? $var->{$clearedItem} : @$value)}}" {!! $p !!}>

		@elseif($type === 'password')
			<input type="password" name="{{ $item }}" {!! $p !!}>

		@elseif($type === 'select' || $type === 'multiSelect')
			<?php
				$listedItems = isset($list_fixed) ? $list_fixed : (count($list) ? $list->lists($type === 'multiSelect' ? 'name' : 'title', 'id') : []);
				if($type === 'multiSelect') {
					$selectedItemId = old($item) ? old($item) : (isset($selected_fixed) ? $selected_fixed : (isset($selected) ? $selected->lists('id')->all() : null));
					$selectedItemId = $selectedItemId ? array_flip($selectedItemId) : null;
				} else
					$selectedItemId = old($item) ? old($item) : (isset($selected_fixed) ? $selected_fixed : (isset($selected) ? $selected->{$item} : null));
			?>

			<select name="{{ $item }}" {!! $p !!}>
				@foreach($listedItems as $id => $title)
					<option value="{{ $id }}" @if($type === 'multiSelect' ? array_get($selectedItemId, $id) !== null : $id == $selectedItemId) selected @endif>{{ $title }}</option>
				@endforeach
			</select>

		@elseif($type === 'textarea')
			<textarea name="{{ $item }}" {!! $p !!}>{{ old($item) ? old($item) : (isset($var) ? $var->{$item} : @$value)}}</textarea>

		@elseif($type == 'checkbox')
			<input type="checkbox" name="{{ isset($name) ? $name : $item }}" value="0" checked class="checkbox" style="display: none">
			<label {!! @$lp !!}>{{ $label }}
				<input type="checkbox" name="{{ isset($name) ? $name : $item }}" value="1"
				       @if(old($item) ? old($item) : (isset($checked) ? $checked : (isset($var) ? $var->{$item} : false))) checked @endif {!! $p !!}>
				<div class="control__indicator"></div>
			</label>

		@elseif($type == 'radio')
			<label {!! @$lp !!}>{{ $label }}
				<input type="radio" name="{{ isset($name) ? $name : $item }}" value="{{ @$value }}"
		            @if(old($item) ? old($item) : (isset($checked) ? $checked : (isset($var) ? $var->{$item} : false))) checked @endif {!! $p !!}>
				<div class="control__indicator-radio"></div>
			</label>

		@elseif($type == 'inputDateTime')
			<input name="{{ $item }}" value="{{ old($item) ? old($item) : (isset($var) ? $var->{$item} : @$value)}}" {!! $p !!}>
			<span class="add-on"><i class="glyphicon glyphicon-calendar"></i></span>

		@elseif($type == 'inputDate')
			<input name="{{ $item }}" value="{{ old($item) ? old($item) : (isset($var) ? $var->{$item} : @$value)}}" {!! $p !!}>
			<i class="glyphicon glyphicon-calendar"></i>

		@elseif($type == 'inputTime')
			<input name="{{ $item }}" value="{{ old($item) ? old($item) : (isset($var) && $var->{$item} != '00:00:00' ? $var->setDateFormat($item) : (isset($value) ? $value : (isset($to) ? '00:01' : '00:00'))) }}" {!! $p !!}>
{{--			{!! Form::text($item, isset($var) && $var->{$item} != '00:00:00' ? $var->setDateFormat($item): (old($item) ? $var->setDateFormat($item, old($item)) : (isset($to_{$item}) ? '00:01' : '00:00')), $p) !!}--}}
			<i class="glyphicon glyphicon-time"></i>

		@elseif($type == 'imageUpload')
			<div class="imageUpload big" data-target="{{ route('uploadImage') }}" data-token="{{ csrf_token() }}">
				<section>
					<div class="col-md-4">
						<div class="img-wrapper thumbnail">
							<a id="addphoto" class="imageBrowse">
								@if (isset($var) && $var->image)
									<i class="fa fa-plus-circle" aria-hidden="true"></i>
									<img class="has-value" src="{{ $var->setImage('500_130', 'image', 'resize') }}" id="img" alt="{{ $var->setImage('500_130', 'image', 'resize') }}">
									<img class="no-value hidden" src="" alt="">
								@elseif (old($item))
									<img class="has-value" src="{{ old($item) ? $var->setImage('500_130', old($item), 'resize') : '' }}" id="img" alt="{{ old($item) ? $var->setImage('500_130', old($item), 'resize') : '' }}">
									<img class="no-value hidden" src="" alt="">
								@else
									<img class="has-value hidden" src="" id="img" alt="">
									<img class="no-value" src="http://placehold.it/300x250" alt="">
								@endif
							</a>
							{{--<a id="removephoto" class="btn btn-danger imageRemove">Убрать</a>--}}
						</div>
					</div>
				</section>
				<div class="errors"></div>
				<input name="{{ $item }}" value="{{ isset($var) && $var->{$item} ? $var->{$item} : old($item) }}" type="hidden" class="imageValue" id="photo" accept="image/*;capture=camera" capture="camera"/>
			</div>

		@elseif($type == 'imageUploadMultiple')
			<div class="imageUploadMultiple" data-target="{{ route('uploadImage') }}" data-token="{{ csrf_token() }}">
				<div class="row form-group images-group ">
					<?php $photos = $var->photos->filter(function ($item) use ($var) {return $item['path'] != $var->image;}) ?>
					<?php isset($only) ? $photos = $var->photos->filterFix('type', '=', $only) : true ?>
					@if(isset($var) && count($photos) > 0 || old($item))
						@foreach (isset($var) && count($photos) > 0 ? $photos : explode(',', old($item)) as $image)
							<div class="imageThumbnail col-md-3">
								<div class="thumbnail">
									<img data-value="{{ isset($var) ? $image->path : $image }}" src="{{ asset(config('admin.imagesUploadDirectory') . '/' . (isset($var) > 0 ? $image->path : $image)) }}"/>
									<a href="#" class="imageRemove"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
								</div>
							</div>
						@endforeach
					@endif
					<div class="imageBrowse col-md-3">
						<div class="button">

						</div>
					</div>
					<div class="errors"></div>
					<input name="{{ $item }}" class="imageValue" type="hidden" value="{{ isset($var) && count($photos) > 0 ? implode(',', $photos->lists('path')->all()) : old($item)}}">
				</div>
			</div>

		@elseif($type == 'fileUpload')
			<div class="imageUpload" data-target="{{ route('uploadFile') }}" data-token="{{ csrf_token() }}">
				<div class="relative" style="position: relative;">
					<div class="resumse download">
						<div class="thumbnail " >
							<div class="no-value">
								<span>{{ isset($var) && $var->{$item} ? $var->{$item} : (old($item) ? old($item) : 'Файл не загружен') }}</span>
							</div>
							<div class="has-value hidden">
								<a href="{{ route('home') }}" data-toggle="tooltip" title="Скачать"><i class="fa fa-fw fa-file-o"></i> <span></span></a>
							</div>
						</div>
						<div class="befores" >
							<div class="btn btn-primary imageBrowse" style="border-radius: 0; background: #2f9cf3; border: 0"><i class="fa fa-upload"></i>Выберите файл</div>
							{{--<div class="btn btn-danger imageRemove"><i class="fa fa-times"></i>Удалить</div>--}}
						</div>
					</div>
					<div class="errors"></div>
					<input name="{{ $item }}" value="{{ isset($var) && $var->{$item} ? $var->{$item} : old($item) }}" type="hidden" class="imageValue">
				</div>
			</div>
		@endif

	@if(@$sdp)
		</div>
	@endif
	@if($errors->has($errorItem))
		<span class="help-block">{{ $errors->first($errorItem) }}</span>
	@endif
@if(@$dp != 'no-div')
	</div>
@endif