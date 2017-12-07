<?php if (empty($parent_id)): ?>
	<?php $parent_id = NULL ?>
<?php endif ?>
<?php if (empty($lvl)): ?>
	<?php $lvl = 0 ?>
<?php endif ?>
<?php foreach (ORM::factory($field['model'])->where('parent_id', '=', $parent_id)->order_by('sort', 'ASC')->find_all() as $key2 => $item): ?>
	<div <?php echo ($parent_id == NULL) ? 'class="recursive_checkbox"' : '' ?>>
		<label style="margin-left: <?php echo $lvl * 10 ?>px">
			<input type="checkbox" name="<?php echo $key ?>[]" value="<?php echo $item->{$field['id_field']} ?>" <?php echo ($model->has($key, $item) || in_array($item->id, $selected_ids) ? 'checked="checked"' : '') ?>> <?php echo HTML::chars($item->{$field['title_field']}) ?>
		</label>
		<?php echo View::factory('esup_pieces/form/_many_to_many_checkbox_recursive', array(
			'model' => $model, 
			'key' => $key, 
			'field' => $field, 
			'selected_ids' => $selected_ids,
			'current_id' => $current_id,
			'parent_id' => $item->id,
			'lvl' => $lvl + 1,
		)) ?>
	</div>
<?php endforeach ?>