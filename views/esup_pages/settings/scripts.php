<!-- Codeeditor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/mode/javascript/javascript.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.29.0/theme/ambiance.min.css">
<style>.CodeMirror { height: 200px }</style>
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
					<textarea name="css" class="form-control" id="form_css" cols="30" rows="10"><?php echo $css ?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label for="form_js" class="col-sm-2 col-form-label">JS</label>
				<div class="col-sm-10">
					<textarea name="js" class="form-control" id="form_js" cols="30" rows="10"><?php echo $js ?></textarea>
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
<script>
	var myCodeMirror = CodeMirror.fromTextArea(form_css, {
		lineNumbers: true,
		theme: 'ambiance'
	});
</script>
<script>
	var myCodeMirror = CodeMirror.fromTextArea(form_js, {
		lineNumbers: true,
		theme: 'ambiance'
	});
</script>