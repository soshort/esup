<div class="form-group">
	<label for="form_<?php echo $key ?>" class="col-sm-2 control-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<input type="text" class="form-control datepicker" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo $key ?>" value="<?php echo ($model->$key == '') ? date('d-m-Y H:i:s') : date('d-m-Y H:i:s', $model->$key) ?>">
	</div>
</div>