<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<div id="map_<?php echo $key ?>" style="width: 100%; height: 300px"></div>
		<input type="hidden" name="<?php echo $key ?>" id="form_<?php echo $key ?>" value="<?php echo ($model->$key == '') ? '43.295904,76.943776:10' : $model->$key ?>" />
	</div>
</div>
<?php
	$map_data = explode(':', ($model->$key == '') ? '43.295904,76.943776:10' : $model->$key);
	$coordinates = explode(',', $map_data[0]);
	$lat = $coordinates[0]; $lng = $coordinates[1]; $zoom = $map_data[1];
?>
<script type="text/javascript">
    ymaps.ready(function(){
    	var mapKey = '<?php echo $key ?>';
    	var mapId = 'map_' + mapKey;
    	var lat = <?php echo $lat ?>;
    	var lng = <?php echo $lng ?>;
        var lat_lng_str = '<?php echo $map_data[0] ?>';
    	var zoom = <?php echo $zoom ?>;
        var map = new ymaps.Map(mapId, {
            center: [lat, lng],
            zoom: zoom
        });
		map.events.add('boundschange', function(e){
            var zoom = map.getZoom();
            $('#form_' + mapKey).val(lat_lng_str + ':' + zoom);
		});
        map.controls.add('zoomControl', {
            left: '20',
            top: '20'
        });
        map.behaviors.enable('scrollZoom');
        var place = new ymaps.Placemark([lat, lng], {
            hintContent: 'Укажите местоположение нового места'
        }, {
            draggable: true
        });
        place.events.add('dragend', function(e) {
            var placemarkCoordinates = e.get('target').geometry.getCoordinates();
            lat_lng_str = placemarkCoordinates;
            var mapZoom = map.getZoom();
            $('#form_' + mapKey).val(placemarkCoordinates + ':' + mapZoom);
        });
        map.geoObjects.add(place);
    });
</script>