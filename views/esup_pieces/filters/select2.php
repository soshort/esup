<?php
	$selected_items = array(Arr::get($_GET, $filter_key));
?>
<select class="filter-select2" name="<?php echo $filter_key ?>" data-placeholder="<?php echo $filter['render']['title'] ?>">
	<option></option>
	<?php echo View::factory('esup_pieces/form/select2/items', array(
		'options' => $filter,
		'selected_items' => $selected_items
	)) ?>
</select>