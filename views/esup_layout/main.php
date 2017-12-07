<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" href="/modules/esup/static/favicon.png">
	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<!-- jquery ui -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/ui.datepicker-ru.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/overcast/jquery-ui.css" />
	<!-- bootstrap -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- notifycations -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-noty/2.4.1/packaged/jquery.noty.packaged.min.js" integrity="sha256-2vEdfXRZVGvgd0uRdeWQQsMawZy0r131Vq7ZgduHwgI=" crossorigin="anonymous"></script>
	<!-- css3 animations -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" integrity="sha256-j+P6EZJVrbXgwSR5Mx+eCS6FvP9Wq27MBRC/ogVriY0=" crossorigin="anonymous" />
	<!-- main styles -->
	<link href="/modules/esup/static/css/styles.css" rel="stylesheet" type="text/css" />
	<!-- main scripts -->
	<script src="/modules/esup/static/js/scripts.js"></script>
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