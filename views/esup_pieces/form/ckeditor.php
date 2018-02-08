<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<textarea name="<?php echo $key ?>" class="ckeditor-area" id="form_<?php echo $key ?>"><?php echo htmlspecialchars($model->get_prop($key)) ?></textarea>
	</div>
</div>