<?php
	if ($model->loaded()) {
		$selected_items = array($model->$key);
	} else {
		$selected_items = array(Arr::get($_GET, $key));
	}
?>
<div class="form-group row">
	<label class="col-sm-2 col-form-label"><?php echo $field['render']['title'] ?></label>
	<div class="col-sm-10">
		<select class="form-select2" name="<?php echo $key ?>" data-placeholder="<?php echo $field['render']['title'] ?>">
			<option></option>
			<?php echo View::factory('esup_pieces/form/select2/items', array(
				'options' => $field,
				'selected_items' => $selected_items
			)) ?>
		</select>
	</div>
</div>