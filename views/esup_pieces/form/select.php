<div class="form-group">
	<label for="form_<?php echo $key ?>" class="col-sm-2 control-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<?php echo Form::select($key, $field['values'], $model->$key, array('id' => 'form_'.$key, 'class' => 'form-control')) ?>
	</div>
</div>