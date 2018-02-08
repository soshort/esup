<h3 class="main_header">
	<?php echo $model->options['render']['title'] ?> — карта сайта
	<a href="/esup/<?php echo $model->options['render']['link'].$url_query ?>">Назад к списку</a>
</h3>
<?php echo View::factory('esup_pages/settings/sets_of_settings') ?>
<div>
	<div style="margin-bottom: 1rem">
		<a href="/esup/settings/sitemap?action=generate" class="btn btn-primary">Сгенерировать</a>
		<a href="/esup/settings/sitemap?action=ping" class="btn btn-primary">Пинг</a>
	</div>
	<div style="margin-bottom: 1rem">
		<a class="dotted trigger_xml" href="#">Показать содержимое sitemap.xml</a>
	</div>
	<pre style="display: none">
		<?php echo HTML::entities($raw_sitemap) ?>
	</pre>
</div>
<script type="text/javascript">
	$(function(){
		$('.trigger_xml').click(function(){
			$(this).parent().next().slideToggle('fast');
		});
		return false;
	});
</script>