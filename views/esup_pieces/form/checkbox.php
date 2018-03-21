<?php 
	if ($model->$key === NULL AND isset($field['default']))
	{
		$checked = ($field['default']) ? 1 : 0;
	}
	else
	{
		$checked = $model->$key;
	}
?>
<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<input type="checkbox" id="form_<?php echo $key ?>" name="<?php echo $key ?>" value="<?php echo $model->$key ?>" <?php echo ($checked == 1) ? 'checked="checked"' : '' ?>>
	</div>
</div>