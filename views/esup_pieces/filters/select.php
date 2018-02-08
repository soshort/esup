<?php
	$filter_items = ORM::factory($filter['model'])
		->group_by($filter['render']['model_value_field'])
		->order_by($filter['render']['model_title_field'], 'ASC')
		->find_all();
	$current_filter = ORM::factory($filter['model'], Arr::get($_GET, $filter_key));
	$current_filter_title = ($current_filter->loaded() == FALSE) ? $filter['render']['title'] : $current_filter->{$filter['render']['model_title_field']};
?>
<div class="dropdown filter-select" style="display: inline">
	<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="filter-select-title" data-default-title="<?php echo $filter['render']['title'] ?>"><?php echo $current_filter_title ?></span>
		<input type="hidden" name="<?php echo $filter_key ?>" value="<?php echo $current_filter->id ?>">
	</button>
	<div class="dropdown-menu">
		<a href="#" class="dropdown-item filter-select-item" data-value="">Нет</a>
		<?php foreach ($filter_items as $key => $item): ?>
			<a href="#" class="dropdown-item filter-select-item" data-value="<?php echo $item->{$filter['render']['model_value_field']} ?>"><?php echo $item->{$filter['render']['model_title_field']} ?></a>
		<?php endforeach ?>
	</div>
</div>