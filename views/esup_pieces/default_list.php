<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?>
	<a href="/esup/<?php echo $model->options['render']['link'] ?>/add<?php echo $url_query ?>">Добавить</a>
</h3>
<?php if (isset($model->options['filters'])): ?>
	<?php echo View::factory('esup_pieces/filters/filter_list', array('model' => $model)) ?>
<?php endif ?>
<?php if (isset($model->options['render']['tree_structure'])): ?>
	<?php echo View::factory('esup_pieces/breadcrumbs', array(
		'model' => $model,
	)) ?>
<?php endif ?>
<div class="row main-list">
	<div class="col-md-12">
		<?php if (count($list) > 0): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<tr>
						<th><input type="checkbox" class="multiple-select-all"></th>
						<th>id</th>
						<th><?php echo $model->options['render']['list']['marker_header'] ?></th>
						<?php if (isset($model->options['render']['list']['sort'])): ?>
							<th>Сортировка</th>
						<?php endif ?>
						<th></th>
					</tr>
					<?php foreach ($list as $key => $item): ?>
						<tr>
							<td><input type="checkbox" class="multiple-item" value="<?php echo $item->id ?>"></td>
							<td>
								<?php echo $item->id ?>
								<?php if (isset($model->options['render']['tree_structure'])): ?>
									<?php if ($item->{$model->options['render']['tree_structure']['relation']}->count_all() > 0): ?>
										&nbsp;&nbsp;<a href="/esup/<?php echo $model->options['render']['link'] ?>?<?php echo $model->options['render']['tree_structure']['field'] ?>=<?php echo $item->id ?>" title="Посмотреть дочерние элементы"><span class="glyphicon glyphicon-list"></span></a>
									<?php else: ?>	
										&nbsp;&nbsp;<span class="glyphicon glyphicon-list"></span>
									<?php endif ?>
									&nbsp;&nbsp;<a href="/esup/<?php echo $model->options['render']['link'] ?>/add?<?php echo $model->options['render']['tree_structure']['field'] ?>=<?php echo $item->id ?>" title="Добавить новый элемент в этот пункт"><span class="glyphicon glyphicon-plus"></span></a>
								<?php endif ?>
							</td>
							<td>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?php echo $item->id.$url_query ?>"><?php echo HTML::chars($item->{$model->options['render']['list']['marker_field']}) ?></a>
							</td>
							<?php if (isset($model->options['render']['list']['sort'])): ?>
								<td class="td_sort">
									<input class="form-control input-xs" name="sort" type="text" value="<?php echo $item->{$model->options['render']['list']['sort']['field']} ?>" data-sort-table="<?php echo $item->table_name() ?>" data-sort-field="<?php echo $model->options['render']['list']['sort']['field'] ?>" data-item-id="<?php echo $item->id ?>">
								</td>
							<?php endif ?>
							<td>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/delete/<?php echo $item->id.$url_query ?>" class="pull-right red-link">
									<span class="glyphicon glyphicon-trash"></span>
								</a>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?php echo $item->id.$url_query ?>" class="pull-right" style="margin-right: 20px">
									<span class="glyphicon glyphicon-edit"></span>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</table>
			</div>
			<form action="/esup/<?php echo $model->options['render']['link'] ?>/multiple<?php echo $url_query ?>" method="post" class="multiple-form">
				<input type="hidden" name="items">
				<button name="action" value="delete" class="btn btn-danger multiple-delete" disabled>Удалить выбранные</button>
			</form>
		<?php else: ?>
			<div style="margin-bottom: 20px">
				Нет записей для отображения в этом виде.
			</div>
		<?php endif ?>
		<?php echo Pagination::factory(array(
			'view' => 'esup_pieces/pagination/floating',
			'total_items' => $total_items,
			'items_per_page' => $items_per_page,
		)) ?>
	</div>
</div>