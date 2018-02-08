<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — логотип
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<?php echo View::factory('esup_pages/settings/sets_of_settings') ?>
<form role="form" action="/esup/settings/logo" enctype="multipart/form-data" method="post" id="main_form">
	<ul class="nav nav-tabs">
		<li class="nav-item active"><a class="nav-link active" href="#main" data-toggle="tab">Основные поля</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="main">
			<div class="form-group row">
				<label for="form_css" class="col-sm-2 control-label">Логотип</label>
				<div class="col-sm-10">
					<input type="file" class="form-control" name="logo">
					<div style="margin-top: 15px">
						Текущий логотип:<br><img style="margin-top: 15px" class="img-thumbnail" src="<?php echo $logo ?>">
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label for="form_css" class="col-sm-2 control-label">Favicon</label>
				<div class="col-sm-10">
					<input type="file" class="form-control" name="favicon">
					<div style="margin-top: 15px">
						Текущий favicon:<br><img style="margin-top: 15px" class="img-thumbnail" src="<?php echo $favicon ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<div class="offset-sm-2 col-sm-10">
			<input type="submit" class="btn btn-primary" name="save" value="Сохранить">
		</div>
	</div>
</form>