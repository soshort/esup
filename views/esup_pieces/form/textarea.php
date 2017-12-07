<div class="form-group">
	<label for="form_<?php echo $key ?>" class="col-sm-2 control-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<textarea name="<?php echo $key ?>" class="form-control" id="form_<?php echo $key ?>" rows="3"><?php echo htmlentities($model->get_prop($key)) ?></textarea>
	</div>
</div>