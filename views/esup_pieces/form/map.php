<div class="form-group row">
    <label class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
    <div class="col-sm-10">
        <div id="map_<?php echo $key ?>" data-yandex-maps-key="<?php echo $key ?>" data-lat="<?php echo $model->get_data($model->$key, 'lat') ?>" data-lng="<?php echo $model->get_data($model->$key, 'lng') ?>" data-zoom="<?php echo $model->get_data($model->$key, 'zoom') ?>" style="width: 100%; height: 300px"></div>
        <input type="hidden" name="<?php echo $key ?>" value="<?php echo ($model->$key == '') ? '43.295904,76.943776:10' : $model->$key ?>" />
    </div>
</div>