<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.5.11/full/ckeditor.js"></script>
<!-- Yandex Maps -->
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&amp;lang=ru-RU" type="text/javascript"></script>
<?php
	if (Request::$initial->action() == 'add') {
		$action_title = 'добавление';
		$action_link = '/esup/'.$model->options['render']['link'].'/add'.$url_query;
	} else {
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
		<li class="nav-item"><a href="#main" class="nav-link active" data-toggle="tab">Основные поля</a></li>
		<?php if (isset($model->options['files'])): ?>
			<li class="nav-item"><a class="nav-link" href="#files" data-toggle="tab">Файлы</a></li>
		<?php endif ?>
		<?php if (isset($model->options['many_to_many'])): ?>
			<li class="nav-item"><a class="nav-link" href="#many_to_many" data-toggle="tab">Много ко многим</a></li>
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
				<?php foreach ($model->options['files'] as $field_key => $field): ?>
					<?php echo View::factory('esup_pieces/form/files', array('model' => $model, 'field' => $field, 'key' => $field_key)) ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
		<?php if (isset($model->options['many_to_many'])): ?>
			<div class="tab-pane" id="many_to_many">
				<?php foreach ($model->options['many_to_many'] as $field_key => $field): ?>
					<?php echo View::factory('esup_pieces/form/many_to_many_checkbox', array('model' => $model, 'field' => $field, 'key' => $field_key)) ?>
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
				<input type="button" class="btn btn-secondary" value="Отмена">
			</a>
		</div>
	</div>
</form>
<?php if (isset($model->options['render']['hint'])): ?>
	<div class="alert alert-warning"><?php echo $model->options['render']['hint'] ?></div>
<?php endif ?>
<script type="text/javascript">
	$(function(){
		/* Вкладки по умолчанию */
		var form_action = $('#main_form').attr('action');
		var hash = window.location.hash;
		$('form').attr('action', form_action+hash);
		$('ul.nav a[href="' + hash + '"]').tab('show');
		$(document).on('click', '.nav-tabs a', function(e){
			$(this).tab('show');
			var scrollmem = $('body').scrollTop();
			window.location.hash = this.hash;
			$('form').attr('action', form_action+this.hash);
			$('html,body').scrollTop(scrollmem);
		});

		/* Календарь */
		$('.datepicker').datepicker({
			dateFormat: 'dd.mm.yy',
		    onSelect: function(datetext, inst){
		    	if ($(inst.input).is('.with-time')) {
			        var d = new Date();
			        var h = (d.getHours() < 10 ? '0' : '') + d.getHours();
			        var m = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();
			        var s = (d.getSeconds() < 10 ? '0' : '') + d.getSeconds();
			        datetext = datetext + " " + h + ":" + m + ":" + s;
			        $(this).val(datetext);
		    	}
		    },
		}, $.datepicker.regional['ru']);

		/* Ckeditor */
		CKEDITOR.plugins.addExternal('imagepaste', '/static/lib/ckeditor/custom/plugins/imagepaste/', 'plugin.js');
		$('textarea.ckeditor-area').each(function(){
			CKEDITOR.replace('form_'+$(this).attr('name'), {
			    extraPlugins: 'imagepaste',
			    filebrowserUploadUrl: '/esup/ckfileuploader'
			});
		});
	});
</script>