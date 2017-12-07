<div class="panel panel-primary filter-list">
	<div class="panel-heading"><h3 class="panel-title">Фильтры</h3></div>
	<div class="panel-body" <?php echo (Arr::get($_GET, 'filters') == 'show') ? '' : 'style="display: none"' ?>>
		<form action="/esup/<?php echo $model->options['render']['link'] ?>" method="get">
			<?php foreach ($model->options['filters'] as $filter_key => $filter): ?>
				<?php if ($filter['type'] == 'select'): ?>
					<?php echo View::factory('esup_pieces/filters/select', array('filter' => $filter, 'filter_key' => $filter_key)) ?>
				<?php elseif ($filter['type'] == 'text'): ?>
					<?php echo View::factory('esup_pieces/filters/text', array('filter' => $filter, 'filter_key' => $filter_key)) ?>
				<?php endif ?>
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
			<a href="/esup/<?php echo $model->options['render']['link'] ?>" class="btn btn-default">Сбросить</a>
		</form>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$(document).on('click', '.filter-list .panel-heading', function(){
			var t = $(this);
			t.next().slideToggle(200);
		});

		$(document).on('click', '.filter-select .filter-select-item', function(){
			var t = $(this),
				filterSelectTitle = t.parents('.filter-select').find('.filter-select-title');
			if (t.attr('data-value') == '') {
				filterSelectTitle.text(filterSelectTitle.attr('data-default-title'));
			} else {
				filterSelectTitle.text(t.text());
			}
			t.parents('.filter-select').find('input[type="hidden"]').val(t.attr('data-value'));
			t.parents('.filter-select').find('.dropdown-toggle').dropdown('toggle');
			return false;
		});
	});
</script>