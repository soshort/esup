<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — Epay Kazkom
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<?php echo View::factory('esup_pages/settings/sets_of_settings') ?>
<form class="form-horizontal" role="form" action="/esup/settings/epay" enctype="multipart/form-data" method="post" id="main_form" style="margin-top: 20px">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#main" data-toggle="tab">Основные поля</a></li>
	</ul>
	<div class="tab-content" style="margin-top: 20px">
		<div class="tab-pane active" id="main">
			<?php if ($zip_loaded == FALSE): ?>
				<div class="alert alert-danger">Расширение ZIP не загружено.</div>
			<?php endif ?>
			<div class="form-group">
				<label for="form_css" class="col-sm-2 control-label">ZIP архив</label>
				<div class="col-sm-10">
					<input type="file" class="form-control" name="archive">
				</div>
			</div>
			<div class="form-group">
				<label for="form_test_mode" class="col-sm-2 control-label">Тестовый режим</label>
				<div class="col-sm-10">
					<input type="checkbox" id="form_test_mode" name="test_mode" value="<?php echo $test_mode ?>" <?php echo ($test_mode == 1) ? 'checked="checked"' : '' ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-2">
			<input type="submit" class="btn btn-primary" name="save" value="Сохранить">
		</div>
	</div>
</form>