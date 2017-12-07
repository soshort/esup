<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?>
	<a href="/esup/<?php echo $model->options['render']['link'] ?>/add<?php echo $url_query ?>">Добавить</a>
	<a href="/esup/languages/refresh<?php echo $url_query ?>" title="Добавляет языковые поля в модели которые были созданы после добавления языка">Обновить модели</a>
</h3>
<div class="row main-list">
	<div class="col-md-12">
		<?php if (count($list) > 0): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<tr>
						<th>id</th>
						<th>Язык</th>
						<th>Активен</th>
						<th></th>
					</tr>
					<?php foreach ($list as $key => $item): ?>
						<tr>
							<td><?php echo $item->id ?></td>
							<td>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?= $item->id ?>"><?php echo $item->title ?></a>
							</td>
							<td>
								<input type="checkbox" class="lang_active" id="lg_<?php echo $item->id ?>" <?php echo ($item->active == 1) ? 'checked="checked"' : '' ?> />
							</td>
							<td>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/delete/<?= $item->id ?>" class="pull-right red-link">
									<span class="glyphicon glyphicon-trash"></span>
								</a>
								<a href="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?= $item->id ?>" class="pull-right" style="margin-right: 20px">
									<span class="glyphicon glyphicon-edit"></span>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</table>
			</div>
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
<div class="alert alert-warning">
	Внимание! При удалении языка будет удалена вся информация связанная с ним. Для отключения языка просто снимите галочку в колонке «активен».
</div>
<script type="text/javascript">
	$(function(){
		$('.lang_active').change(function(){
			$.post('/esup/languages/active', { id: $(this).attr('id'), value: $(this).prop('checked') }, function(data){
				if (data.status == 'error') {
					console.log(data.message);
				}
			}, 'JSON');
		});
	});
</script>