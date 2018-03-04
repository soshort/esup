<?php
	if (empty($items)) {
		$related_model = ORM::factory($options['model']);
		if (isset($options['nested'])) {
			$related_model = $related_model->where($options['nested']['field'], '=', NULL);
		}
		$items = $related_model->group_by($options['render']['value_field'])
			->order_by($options['render']['order_field'], $options['render']['order_direction'])
			->find_all();
	}

	if (empty($lvl)) {
		$lvl = 0;
	}
?>
	
<?php foreach ($items as $key => $item): ?>
	<option value="<?php echo $item->{$options['render']['value_field']} ?>" <?php echo ($model->has($relation, $item) || in_array($item->id, $selected_items)) ? 'selected' : '' ?> style="padding: <?php echo $lvl ?>rem"><?php echo $item->get_prop($options['render']['title_field']) ?></option>
	<?php
		if (isset($options['nested'])) {
			$sub_items = ORM::factory($options['model'])
				->where($options['nested']['field'], '=', $item->id)
				->group_by($options['render']['value_field'])
				->order_by($options['render']['order_field'], $options['render']['order_direction'])
				->find_all();
			echo View::factory('esup_pieces/form/select2/multiple_items', array(
				'options' => $options,
				'selected_items' => $selected_items,
				'items' => $sub_items,
				'lvl' => ++$lvl,
				'model' => $model,
				'relation' => $relation
			));
			--$lvl;
		}
	?>
<?php endforeach ?>