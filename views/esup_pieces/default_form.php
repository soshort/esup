<?php
	if (Request::$initial->action() == 'add')
	{
		$action_title = 'добавление';
		$action_link = '/esup/'.$model->options['render']['link'].'/add'.$url_query;
	}
	else
	{
		$action_title = 'редактирование';
		$action_link = '/esup/'.$model->options['render']['link'].'/edit/'.$model->id.$url_query;
	}
?>
<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — <?php echo $action_title ?>
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<form role="form" action="<?php echo $action_link ?>" enctype="multipart/form-data" method="post" id="main_form">
	<ul class="nav nav-tabs">
		<li class="nav-item"><a href="#main" class="nav-link active" data-toggle="tab">Описание</a></li>
		<?php if (isset($model->options['files'])): ?>
			<li class="nav-item"><a class="nav-link" href="#files" data-toggle="tab">Файлы</a></li>
		<?php endif ?>
		<?php if (isset($model->options['many_to_many'])): ?>
			<li class="nav-item"><a class="nav-link" href="#many_to_many" data-toggle="tab">Списки</a></li>
		<?php endif ?>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="main">
			<?php foreach ($model->options['fields'] as $field_key => $field): ?>
				<?php echo View::factory('esup_pieces/form/'.$field['type'], array('model' => $model, 'field' => $field, 'key' => $field_key)) ?>
			<?php endforeach ?>
		</div>
		<?php if (isset($model->options['files'])): ?>
			<div class="tab-pane" id="files">
				<?php foreach ($model->options['files'] as $group_name => $group): ?>
					<?php echo View::factory('esup_pieces/form/files', array('model' => $model, 'group' => $group, 'group_name' => $group_name)) ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
		<?php if (isset($model->options['many_to_many'])): ?>
			<div class="tab-pane" id="many_to_many">
				<?php foreach ($model->options['many_to_many'] as $field_key => $field): ?>
					<?php echo View::factory('esup_pieces/form/many_to_many', array('model' => $model, 'field' => $field, 'key' => $field_key)) ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>
	<div class="form-group row">
		<div class="offset-sm-2 col-sm-10">
			<?php if (Request::$initial->action() == 'add'): ?>
				<input type="submit" class="btn btn-primary" name="add" value="Добавить">
			<?php else: ?>
				<input type="submit" class="btn btn-primary" name="edit" value="Сохранить">
			<?php endif ?>
			<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">
				<input type="button" class="btn" value="Отмена">
			</a>
		</div>
	</div>
</form>
<?php if (isset($model->options['render']['hint'])): ?>
	<div class="alert alert-warning"><?php echo $model->options['render']['hint'] ?></div>
<?php endif ?>
