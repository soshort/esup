<?php
	$id = Arr::get($_GET, $field['default_query_name']);
	if (isset($field['nested']))
	{
		$related_model = ORM::factory($field['model'], $id);
		$selected_items = $related_model->get_ids_tree($related_model);
	}
	else
	{
		$selected_items = array($id);
	}
?>
<div class="form-group row">
	<label class="col-sm-2 col-form-label"><?php echo $field['render']['title'] ?></label>
	<div class="col-sm-10">
		<select name="<?php echo $key ?>[]" class="form-select2" multiple data-placeholder="Нажмите для выбора">
			<?php echo View::factory('esup_pieces/form/select2/multiple_items', array(
				'options' => $field,
				'selected_items' => $selected_items,
				'model' => $model,
				'relation' => $key
			)) ?>
		</select>
	</div>
</div>