<div class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/esup">ESUP</a>
		</div>
		<div class="collapse navbar-collapse">
			<?php foreach ($top_menu as $cat_key => $cat): ?>
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $cat['text'] ?>
							<b class="caret"></b>
							<?php if (isset($cat['eval'])): ?>
								<span class="badge"><?php eval($cat['eval']) ?></span>
							<?php endif ?>
						</a>
						<ul class="dropdown-menu">
							<?php foreach ($cat['list'] as $menu_key => $menu): ?>
								<?php if ($admin->access_level >= $menu['access_level']): ?>
									<li <?php echo (strtolower(Request::$initial->controller()) == $menu['link']) ? 'class="active"' : '' ?>>
										<a href="/esup/<?php echo $menu['link'] ?>">
											<span><?php echo $menu['text'] ?></span>
										</a>
									</li>
								<?php endif ?>
							<?php endforeach ?>
						</ul>
					</li>
				</ul>
			<?php endforeach ?>
			<div class="navbar-form navbar-right">
				Привет, <span style="margin-right: 15px; font-style: italic"><?php echo $admin->fio ?></span>
				<a href="/esup/auth/logout">
					<input type="button" value="Выйти" class="btn btn-danger" />
				</a>
			</div>
			<div class="navbar-form navbar-right">
				<?php foreach ($languages as $key => $language): ?>
					<a href="/esup/languages/set/<?php echo $key ?>" title="<?php echo $language['title'] ?>" class="btn <?php echo ($session->get('lang', 'ru') == $key) ? 'active' : '' ?>"><?php echo $language['visible_name'] ?></a>
				<?php endforeach ?>
			</div>
			<div class="progress progress-striped active pull-right loading">
				<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 150px">
					<span class="sr-only"></span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="custom-container"></div>
<?php echo View::factory('esup_pieces/message') ?>