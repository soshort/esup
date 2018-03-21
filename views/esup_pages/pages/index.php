<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?>
	<a href="/esup/<?php echo $model->options['render']['link'] ?>/add<?php echo $url_query ?>">Добавить</a>
</h3>
<?php if (isset($model->options['filters'])): ?>
	<?php echo View::factory('esup_pieces/filters/filter_list', array('model' => $model)) ?>
<?php endif ?>
<div class="row main-list">
	<div class="col-md-12">
		<?php if (count($list) > 0): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<tr>
						<th><input type="checkbox" class="multiple-select-all"></th>
						<th>id</th>
						<th>Заголовок</th>
						<th>Внутренняя ссылка</th>
						<th>Сортировка</th>
						<th></th>
					</tr>
					<?php foreach ($list as $key => $item): ?>
						<tr>
							<td><input type="checkbox" class="multiple-item" value="<?php echo $item->id ?>"></td>
							<td><?php echo $item->id ?></td>
							<td>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?php echo $item->id.$url_query ?>"><?php echo $item->title ?></a>
							</td>
							<td><span><?php echo $item->link ?></span> <a href="#" class="copy-to-clipboard"><span class="octicon octicon-clippy"></span></a></td>
							<td class="td_sort">
								<input class="form-control form-control-sm" name="sort" type="text" value="<?php echo $item->sort ?>" data-sort-table="<?php echo $item->table_name() ?>" data-sort-field="sort" data-item-id="<?php echo $item->id ?>">
							</td>
							<td>
								<div class="d-flex justify-content-end">
									<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?php echo $item->id.$url_query ?>" class="mr-3"><span class="octicon octicon-pencil"></span></a>
									<a href="/esup/<?php echo $model->options['render']['link'] ?>/delete/<?php echo $item->id.$url_query ?>" class="red-link"><span class="octicon octicon-trashcan"></span></a>
								</div>
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
			<div class="no-records-found">
				Нет записей для отображения в этом виде.
			</div>
		<?php endif ?>
		<?php echo View::factory('esup_pieces/pagination', array(
			'total_items' => $total_items,
			'items_per_page' => $items_per_page,			
		)) ?>
	</div>
</div>
