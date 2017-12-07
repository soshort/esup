<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — просмотр
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<ul class="nav nav-tabs" style="margin-top: 20px">
	<li class="active"><a href="#main" data-toggle="tab">Содержание письма</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="main">
		<table class="table" style="margin-top: 20px">
			<tr>
				<th style="width: 20%">Email</th>
				<td>
					<?php if ($model->_to[0] == '{' || $model->_to[0] == '['): ?>
						<?php $arr = json_decode($model->_to, TRUE); foreach ($arr as $e_key => $email): ?>
							<?php echo $email; echo (count($arr) == $e_key + 1) ? ',' : '' ?>
						<?php endforeach ?>
					<?php else: ?>
						<?php echo $model->_to ?>
					<?php endif ?>
				</td>
			</tr>
			<tr>
				<th style="width: 20%">Тема письма</th>
				<td><?php echo $model->subject ?></td>
			</tr>
			<tr>
				<th style="width: 20%">Текст письма</th>
				<td>
					<?php echo $model->message ?>
				</td>
			</tr>
			<tr>
				<th style="width: 20%">Статус</th>
				<td>
					<?php if ($model->status == 0): ?>
						<span class="label label-default">Не отправлено</span>
						<a href="/esup/mailer/send/<?php echo $model->id.$url_query ?>" class="label_link">Отправить</a>
					<?php elseif ($model->status == 1): ?>
						<span class="label label-success">Отправлено</span>
					<?php elseif ($model->status == 2): ?>
						<span class="label label-danger">Ошибка</span>
						<a href="/esup/mailer/send/<?php echo $model->id.$url_query ?>" class="label_link">Отправить</a>
					<?php endif ?>
				</td>
			</tr>
			<tr>
				<th style="width: 20%">Ответ мейлера</th>
				<td>
					<?php echo $model->mailer_response ?>
				</td>
			</tr>
		</table>
	</div>
</div>