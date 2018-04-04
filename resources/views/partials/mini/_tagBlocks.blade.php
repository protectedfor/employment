<?php
	$filteredArr = \App\Models\Vars::getFilteredVars(get_defined_vars(), 'b_');
	foreach ($filteredArr  as $k => $f) {
		$k = str_replace(["b_", "_"], '', $k);
		if(is_array($f))
			${$k} = array_get($f, $b_item);
		else
			${$k} = $f;
	}
	$var = isset($b_var_{$b_item}) ? $b_var_{$b_item} : (new \App\Models\BaseModel());
	if($type == 'img') {
		$imageSize = explode('_', isset($size) ? $size : '50_50');
		$w_size = array_get($imageSize, 0);
		$h_size = array_get($imageSize, 1) !== 'null' ? array_get($imageSize, 1) . 'px' : 'auto';
		$defaultClass = @$flex !== 'no' ? "class=\"b-img-wrapper b-bg-plus\"" : "class=\"img-wrapper\"";
		$defaultStyle = @$flex !== 'no' ? "style=\"height: {$h_size};background: #f7fafb;\"" : "";
	}
	$p = @$p;
?>

@if(@$dp != 'no-div')
	<div {!! @$dp ? $dp . ' ' . @$defaultClass : @$defaultClass !!} {!! @$defaultStyle !!}>
@endif
	@if(@$sdp)
		<div class="{{ $sdp }}">
	@endif

		@if($type === 'img')
			<img src="{{ $var->setImage(isset($size) ? $size : '50_50', isset($src) ? $src : '/no', isset($mod) ? $mod : 'fit' ) }}"
			     alt="{{ isset($alt) && $alt ? $alt : ($var ? $var->title : '')}}" {!! $p !!} />

		@elseif($type === 'file')
			<?php $fullPath = config('admin.imagesUploadDirectory'). '/'. $var->{$src}; ?>
			@if($var->{$src} && file_exists($fullPath))
				<?php $fileSize = round(filesize($fullPath)/1000000) > 0 ? round(filesize($fullPath)/1000000) . ' мб' :
						(round(filesize($fullPath)/1000) ? round(filesize($fullPath)/1000) . ' кб' : '1 кб') ?>
				<a class="doc" href="{{ url(config('admin.imagesUploadDirectory'). '/'. $var->{$src}) }}" {!! $p !!}>
					{{ $text }}<span> ({{ $fileSize ? $fileSize : 1 }})</span><span class="glyphicon glyphicon-download-alt"></span>
				</a>
			@else
				<a href="#">@if($var->{$src}) Неверное имя файла @else Файл не существует @endif<span class="glyphicon glyphicon-download-alt"></span></a>
			@endif

		@elseif($type === 'map')
			<?php
				$size = isset($size) && $size ? "&size={$size[0]},{$size[1]}" : '&size=650,300';
				$center = isset($b_center_{$b_item}) ? "&ll={$b_center_{$b_item}}" : '';
				$scale = isset($b_scale_{$b_item}) ? $b_scale_{$b_item} : '14';
				$class = $var ? last(explode('\\', get_class($var))) : false;
				$coordsArr = (is_string($src) && $var->{$src}) ? explode(',', $var->{$src}) : $src;
				for ($i = 0; $i <= count($coordsArr) - 1; $i = $i + 2) {
					if($i === 0)
						$coords = "&pt={$coordsArr[$i + 1]},{$coordsArr[$i]},pm2dbm";
					else
						$coords = "{$coords}~{$coordsArr[$i + 1]},{$coordsArr[$i]},pm2dbm";
				}
				$link = "https://static-maps.yandex.ru/1.x/?l=map{$size}&z={$scale}{$coords}";
				$arr = ['coords' => $coordsArr, 'title' => $class === 'Trener' ? $var->FullName : $var->title, 'scale' => $scale];
			?>
			<a class="ajax_modal" data-action="show" data-type="map" data-id="" data-parameters="{{  json_encode($arr) }}" href=""><img src="{{ $link }}"></a>
		@endif

	@if(@$sdp)
		</div>
	@endif
@if(@$dp != 'no-div')
	</div>
@endif