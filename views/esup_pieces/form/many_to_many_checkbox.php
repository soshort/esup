<?php
	$id_field = Arr::get($field, 'default_query_name');
	if (isset($field['recursive']) && $field['recursive'] == TRUE) {
		$current_id = Arr::get($_GET, $id_field);
		$current_model = ORM::factory($field['model'], $current_id);
		$selected_ids = $current_model->get_ids_tree($current_model);
	} else {
		$current_id = Arr::get($_GET, $id_field);
		$selected_ids = array($current_id);
	}
?>
<div class="bs-callout bs-callout-warning">
	<h4><?php echo $field['label'] ?>:</h4>
	<div>
		<?php if (isset($field['recursive']) && $field['recursive'] == TRUE): ?>
			<?php echo View::factory('esup_pieces/form/_many_to_many_checkbox_recursive', array(
				'model' => $model,
				'key' => $key,
				'field' => $field,
				'selected_ids' => $selected_ids,
				'current_id' => $current_id
			)) ?>
		<?php else: ?>
			<?php foreach (ORM::factory($field['model'])->find_all() as $key2 => $item): ?>
				<label>
					<input type="checkbox" name="<?php echo $key ?>[]" value="<?php echo $item->{$field['id_field']} ?>" <?php echo ($model->has($key, $item) || in_array($item->id, $selected_ids) ? 'checked="checked"' : '') ?>> <?php echo HTML::chars($item->{$field['title_field']}) ?>
				</label>
			<?php endforeach ?>
		<?php endif ?>
		<div style="clear: both"></div>
	</div>
</div>