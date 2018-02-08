<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?>
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
						<th></th>
						<th>Оригинальное название</th>
						<th>Дата загрузки</th>
						<th></th>
					</tr>
					<?php foreach ($list as $key => $item): ?>
						<tr>
							<td><input type="checkbox" class="multiple-item" value="<?php echo $item->id ?>"></td>
							<td><?php echo $item->id ?></td>
							<td><a target="_blank" href="/static/uploads/files/<?php echo $item->file ?>"><span class="octicon octicon-browser"></span></a></td>
							<td>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?php echo $item->id ?>"><?php echo $item->original_name ?></a>
							</td>
							<td><?php echo date('d-m-Y H:i:s', $item->creation_time) ?></td>
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