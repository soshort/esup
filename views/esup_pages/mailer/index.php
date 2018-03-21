<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?>
	<a href="/esup/mailer/send_all<?php echo $url_query ?>" title="Разослать все не отправленные письма">Разослать все</a>
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
						<th>Для</th>
						<th>Статус</th>
						<th></th>
					</tr>
					<?php foreach ($list as $key => $item): ?>
						<tr>
							<td><input type="checkbox" class="multiple-item" value="<?php echo $item->id ?>"></td>
							<td><?php echo $item->id ?></td>
							<td>
								<?php if (empty($item->_to) == FALSE AND ($item->_to[0] == '{' OR $item->_to[0] == '[')): ?>
									<a href="/esup/mailer/view/<?php echo $item->id.$url_query ?>">
										<?php $arr = json_decode($item->_to, TRUE); foreach ($arr as $e_key => $email): ?>
											<?php echo $email; echo (count($arr) == $e_key + 1) ? ',' : '' ?>
										<?php endforeach ?>
									</a>
								<?php else: ?>
									<a href="/esup/mailer/view/<?php echo $item->id.$url_query ?>">
										<?php echo $item->_to ?>
									</a>
								<?php endif ?>
							</td>
							<td>
								<?php if ($item->status == 0): ?>
									<span class="badge badge-secondary">Не отправлено</span>
									<a href="/esup/mailer/send/<?php echo $item->id.$url_query ?>">Отправить</a>
								<?php elseif ($item->status == 1): ?>
									<span class="badge badge-success">Отправлено</span>
								<?php elseif ($item->status == 2): ?>
									<span class="badge badge-danger">Ошибка</span>
									<a href="/esup/mailer/send/<?php echo $item->id.$url_query ?>">Отправить</a>
								<?php endif ?>
							</td>
							<td>
								<div class="d-flex justify-content-end">
								<a href="/esup/mailer/delete/<?php echo $item->id.$url_query ?>" class="red-link"><span class="octicon octicon-trashcan"></span></a>
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