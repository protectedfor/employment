@if(Request::url() == route('employers.profile.edit') || Request::url() == route('trainings.create') || (isset($training) && Request::url() == route('trainings.edit', $training->id)))
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAByXHJTS_tDZvRl9d1fhb1QmlPU8Cf0wQ" type="text/javascript"></script>
	<script src="{{ asset('js/gmaps.js') }}"></script>
@endif

<label>Положение на карте</label>
<div class="form-group ">
    <div id="map" style="height: 400px;"></div>
</div>
<a class="btn btn-primary add_marker" href=""><i class="fa fa-plus"></i> Добавить маркер</a>
<p>Маркеров на карте: <span class="markers_count"></span></p>
<script>
	window.onload = function () {
		$(function () {

			markersInput = $('*[name=google_map_code]');

			var markersCount = $('.markers_count');

			@if((isset($instance) && $instance->google_map_code && $instance->google_map_code != '[]') || old('google_map_code'))
				<?php isset($instance) && $instance->google_map_code ? $geoPosition = array_first(json_decode($instance->google_map_code), function($key, $value){return $key == 0;}) :
				 $geoPosition = array_first(json_decode(old('google_map_code')), function($key, $value){return $key == 0;}) ?>
				map = new GMaps({
					zoom: 16,
					div: '#map',
					lat: "<?= $geoPosition[0] ?>",
					lng: "<?= $geoPosition[1] ?>"
				});

				strMarkers = "<?= isset($instance) && $instance->google_map_code ? $instance->google_map_code : old('google_map_code') ?>";
				var parsedMarkers = JSON.parse(strMarkers);

				$.each(parsedMarkers, function (k, v) {
					map.addMarker({
						lat: v[0],
						lng: v[1],
						draggable: true,
						dragend: function () {
							var markers = [];
							$.each(map.markers, function (k, v) {
								markers[k] = [v.getPosition().lat(), v.getPosition().lng()];
							});
							markersInput.val(JSON.stringify(markers));
							markersCount.html(map.markers.length);

						},
						click: function (e) {
							map.removeMarker(this);
							var markers = [];
							$.each(map.markers, function (k, v) {
								markers[k] = [v.getPosition().lat(), v.getPosition().lng()];
							});
							markersInput.val(JSON.stringify(markers));
							markersCount.html(map.markers.length);
						}
					});
				});

				markersCount.html(map.markers.length);

			@else
				map = new GMaps({
					zoom: 17,
					div: '#map',
					lat: 42.87384116352395,
					lng: 74.61088562011719
				});
			@endif

			markers = [];
			$('.add_marker').on('click', function (e) {
				map.addMarker({
					lat: map.getCenter().lat(),
					lng: map.getCenter().lng(),
					draggable: true,
					dragend: function () {
						var markers = [];
						$.each(map.markers, function (k, v) {
							markers[k] = [v.getPosition().lat(), v.getPosition().lng()];
						});
						markersInput.val(JSON.stringify(markers));
						markersCount.html(map.markers.length);

					},
					click: function (e) {
						map.removeMarker(this);
						var markers = [];
						$.each(map.markers, function (k, v) {
							markers[k] = [v.getPosition().lat(), v.getPosition().lng()];
						});
						markersInput.val(JSON.stringify(markers));
						markersCount.html(map.markers.length);
					}
				});
				return false;
			});
		});
	}
</script>