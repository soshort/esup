<!doctype html>
<html lang="ru">
<head>
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" href="/modules/esup/static/images/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php
		echo $assets->single->css();
		echo $assets->pipeline->css();
		echo $assets->single->js();
		echo $assets->pipeline->js();
	?>
</head>
<body>
	<?php echo $header ?>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<?php echo $content ?>
			</div>
		</div>
	</div>
	<?php echo $footer ?>
</body>
</html>