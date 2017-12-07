<div>
	<a href="/esup/settings" class="<?php echo (Request::$initial->action() == 'index') ? 'active' : '' ?>">основные</a>
	<a href="/esup/settings/scripts" class="<?php echo (Request::$initial->action() == 'scripts') ? 'active' : '' ?>">скрипты</a>
	<a href="/esup/settings/logo" class="<?php echo (Request::$initial->action() == 'logo') ? 'active' : '' ?>">логотип</a>
	<a href="/esup/settings/epay" class="<?php echo (Request::$initial->action() == 'epay') ? 'active' : '' ?>">Epay Kazkom</a>
	<a href="/esup/settings/sitemap" class="<?php echo (Request::$initial->action() == 'sitemap') ? 'active' : '' ?>">карта сайта</a>
</div>