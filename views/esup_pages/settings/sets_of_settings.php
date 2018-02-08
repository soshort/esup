<?php $action = Request::$initial->action() ?>
<div class="btn-group btn-group-sm settings-sets" role="group" aria-label="SetsOfSettings">
	<a class="btn btn-secondary <?php echo ($action == 'index') ? 'active' : '' ?>" href="/esup/settings">Основные</a>
	<a class="btn btn-secondary <?php echo ($action == 'scripts') ? 'active' : '' ?>" href="/esup/settings/scripts">Скрипты</a>
	<a class="btn btn-secondary <?php echo ($action == 'logo') ? 'active' : '' ?>" href="/esup/settings/logo">Логотип</a>
	<a class="btn btn-secondary <?php echo ($action == 'epay') ? 'active' : '' ?>" href="/esup/settings/epay">Epay KKB</a>
	<a class="btn btn-secondary <?php echo ($action == 'sitemap') ? 'active' : '' ?>" href="/esup/settings/sitemap">Карта сайта</a>	
</div>