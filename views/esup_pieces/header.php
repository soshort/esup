<nav class="navbar navbar-expand-lg navbar-light bg-light esup-navbar">
	<a class="navbar-brand" href="/esup">ESUP</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="mainMenu">
		<ul class="navbar-nav mr-auto">
			<?php foreach ($top_menu as $cat_key => $cat): ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="menuDropdown<?php $cat_key ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $cat['text'] ?></a>
					<div class="dropdown-menu" aria-labelledby="menuDropdown<?php $cat_key ?>">
						<?php foreach ($cat['list'] as $menu_key => $menu): ?>
							<?php if ($admin->access_level >= $menu['access_level']): ?>
								<a class="dropdown-item <?php echo (strtolower(Request::$initial->controller()) == $menu['link']) ? 'active' : '' ?>" href="/esup/<?php echo $menu['link'] ?>"><?php echo $menu['text'] ?></a>
							<?php endif ?>
						<?php endforeach ?>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
		<form class="form-inline mr-sm-2 d-none loading">
			<div class="progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width: 100%"></div>
			</div>
		</form>
		<span class="navbar-text mr-sm-2 menu-languages">
			<?php foreach ($languages as $key => $language): ?>
				<a href="/esup/languages/set/<?php echo $key ?>" title="<?php echo $language['title'] ?>"><button class="btn <?php echo ($session->get('lang', 'ru') == $key) ? '' : 'btn-light' ?>" type="button"><?php echo $language['visible_name'] ?></button></a>
			<?php endforeach ?>
		</span>
		<span class="navbar-text mr-sm-2">Привет, <?php echo $admin->fio ?></span>
		<span class="navbar-text">
			<a href="/esup/auth/logout" class="btn btn-danger text-light">Выйти</a>
		</span>
	</div>
</nav>
<?php echo View::factory('esup_pieces/message') ?>