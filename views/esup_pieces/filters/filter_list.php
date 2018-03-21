<div class="card bg-light filter-list">
	<div class="card-header border-0 rounded">Фильтры</div>
	<div class="card-body" <?php echo (Arr::get($_GET, 'filters') == 'show') ? '' : 'style="display: none"' ?>>
		<form action="/esup/<?php echo $model->options['render']['link'].$url_query ?>" method="get">
			<?php foreach ($model->options['filters'] as $filter_key => $filter): ?>
				<?php echo View::factory('esup_pieces/filters/'.$filter['type'], array('filter' => $filter, 'filter_key' => $filter_key)) ?>
			<?php endforeach ?>
			<?php if (isset($bind_params)): ?>
				<?php foreach ($bind_params as $p_key => $param): ?>
					<?php $param_value = Arr::get($_GET, $param); if ($param_value): ?>
						<input type="hidden" name="<?php echo $param ?>" value="<?php echo $param_value ?>">
					<?php endif ?>
				<?php endforeach ?>
			<?php endif ?>
			<input type="hidden" name="filters" value="show">
			<input type="submit" class="btn btn-primary" value="Применить">
			<a href="/esup/<?php echo $model->options['render']['link'] ?>">
				<button class="btn" type="button">Сбросить</button>
			</a>
		</form>
	</div>
</div>
