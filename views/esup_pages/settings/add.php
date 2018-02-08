<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — добавление
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<form role="form" action="/esup/settings/add<?php echo $url_query ?>" enctype="multipart/form-data" method="post" id="main_form">
	<ul class="nav nav-tabs">
		<li class="nav-item"><a class="nav-link active" href="#main" data-toggle="tab">Основные поля</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="main">
			<div class="form-group row">
				<label for="form_set" class="col-sm-2 control-label">Название сета</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_set" placeholder="Название сета" name="set" value="<?php echo (Request::current()->query('set')) ? Request::current()->query('set') : 'site' ?>">
				</div>
			</div>
			<div class="form-group row">
				<label for="form_title" class="col-sm-2 control-label">Заголовок</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_title" placeholder="Заголовок" name="title">
				</div>
			</div>
			<div class="form-group row">
				<label for="form_name" class="col-sm-2 control-label">Имя</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_name" placeholder="Имя" name="name">
				</div>
			</div>
			<div class="form-group row">
				<label for="form_value" class="col-sm-2 control-label">Значение</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="form_value" placeholder="Значение" name="value">
				</div>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<div class="offset-sm-2 col-sm-10">
			<?php if (Request::$initial->action() == 'add'): ?>
				<input type="submit" class="btn btn-primary" name="add" value="Добавить">
			<?php else: ?>
				<input type="submit" class="btn btn-primary" name="edit" value="Сохранить">
			<?php endif ?>
			<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">
				<input type="button" class="btn btn-secondary" value="Отмена">
			</a>
		</div>
	</div>
</form>