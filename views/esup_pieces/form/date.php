<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<?php if (Arr::get($field, 'time')): ?>
			<input type="text" class="form-control datepicker with-time" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo $key ?>" value="<?php echo ($model->$key == '') ? date('d.m.Y H:i:s') : date('d.m.Y H:i:s', $model->$key) ?>">
		<?php else: ?>
			<input type="text" class="form-control datepicker" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo $key ?>" value="<?php echo ($model->$key == '') ? date('d.m.Y') : date('d.m.Y', $model->$key) ?>">
		<?php endif ?>
	</div>
</div>