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
							<td><span><?php echo $item->link ?></span> <a href="#" class="copy-to-clipboard"><span class="glyphicon glyphicon-copy"></span></a></td>
							<td class="td_sort">
								<input class="form-control input-xs" name="sort" type="text" value="<?php echo $item->sort ?>" data-sort-table="<?php echo $item->table_name() ?>" data-sort-field="sort" data-item-id="<?php echo $item->id ?>">
							</td>
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
			'items_per_page' => $items_per_page
		)) ?>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('table').on('click', '.copy-to-clipboard', function(){
			var t = $(this),
				tmp = $('<input>');
			$('body').append(tmp);
			tmp.val(t.prev().text()).select();
			document.execCommand('copy');
			tmp.remove();
			return false;
		});
	});
</script>