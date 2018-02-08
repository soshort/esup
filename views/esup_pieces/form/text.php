<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label">
		<?php echo $field['label'] ?>
		<?php if (isset($field['hint'])): ?>
			<a href="javascript:0" data-toggle="tooltip" data-placement="top" title="<?php echo $field['hint'] ?>"><span class="octicon octicon-question"></span></a>
		<?php endif ?>
	</label>
	<div class="col-sm-10">
		<?php if (isset($field['translate'])): ?>
			<input type="text" class="form-control" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo $key ?>" value="<?php echo htmlentities($model->get_prop($key)) ?>">
		<?php else: ?>
			<input type="text" class="form-control" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo $key ?>" value="<?php echo htmlentities($model->$key) ?>">
		<?php endif ?>
	</div>
</div>