<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — редактирование
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<form class="form-horizontal" role="form" action="/esup/<?php echo $model->options['render']['link'] ?>/edit/<?php echo $model->id ?>" enctype="multipart/form-data" method="post" id="main_form" style="margin-top: 20px">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#main" data-toggle="tab">Основные поля</a></li>
	</ul>
	<div class="tab-content" style="margin-top: 20px">
		<div class="tab-pane active" id="main">
			<div class="form-group">
				<label for="form_key" class="col-sm-2 control-label">Ключ</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_key" placeholder="Ключ" name="key" value="<?php echo $model->key ?>" disabled="disabled">
				</div>
			</div>
			<div class="form-group">
				<label for="form_title" class="col-sm-2 control-label">Заголовок</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_title" placeholder="Заголовок" name="title" value="<?php echo $model->title ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="form_visible_name" class="col-sm-2 control-label">Видимый на сайте заголовок</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_visible_name" placeholder="Видимый на сайте заголовок" name="visible_name" value="<?php echo $model->visible_name ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="form_active" class="col-sm-2 control-label">Активен</label>
				<div class="col-sm-10">
					<input type="checkbox" id="form_active" name="active" <?php echo ($model->active == 1) ? 'checked="checked"' : '' ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<?php if (Request::$initial->action() == 'add'): ?>
				<input type="submit" class="btn btn-primary" name="add" value="Добавить">
			<?php else: ?>
				<input type="submit" class="btn btn-primary" name="edit" value="Сохранить">
			<?php endif ?>
			<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">
				<input type="button" class="btn btn-default" value="Отмена">
			</a>
		</div>
	</div>
</form>