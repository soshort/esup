<style>
	pre.ace_editor {
		height: 200px;
		border: solid 1px #dee2e6;
	}
</style>
<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — скрипты
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<?php echo View::factory('esup_pages/settings/sets_of_settings') ?>
<form role="form" action="/esup/settings/scripts" enctype="multipart/form-data" method="post" id="main_form">
	<ul class="nav nav-tabs">
		<li class="nav-item active"><a class="nav-link active" href="#main" data-toggle="tab">Описание</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="main">
			<div class="form-group row">
				<label for="form_css" class="col-sm-2 col-form-label">CSS</label>
				<div class="col-sm-10">
					<textarea id="form_css" data-editor="css" data-hidden-name="css"><?php echo $css ?></textarea>
					<input type="hidden" name="css">
				</div>
			</div>
			<div class="form-group row">
				<label for="form_js" class="col-sm-2 col-form-label">JS</label>
				<div class="col-sm-10">
					<textarea id="form_js" data-editor="javascript" data-hidden-name="js"><?php echo $js ?></textarea>
					<input type="hidden" name="js">
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