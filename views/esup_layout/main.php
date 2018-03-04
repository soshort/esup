<!doctype html>
<html lang="ru">
<head>
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" href="/modules/esup/static/favicon.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- Popper -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!-- Octicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/4.4.0/font/octicons.min.css" integrity="sha256-pNGG0948CVwfHxxS8lVkUKftaSsMBzFSUknrKr2utfY=" crossorigin="anonymous">
	<!-- Css3 animations -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" integrity="sha256-j+P6EZJVrbXgwSR5Mx+eCS6FvP9Wq27MBRC/ogVriY0=" crossorigin="anonymous">
	<!-- JQuery ui -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/ui.datepicker-ru.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/overcast/jquery-ui.css">
	<!-- Notifycations -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-noty/2.4.1/packaged/jquery.noty.packaged.min.js" integrity="sha256-2vEdfXRZVGvgd0uRdeWQQsMawZy0r131Vq7ZgduHwgI=" crossorigin="anonymous"></script>
	<!-- Select2 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/i18n/ru.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
	<!-- Fancybox -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js" ></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" >
	<!-- Main styles -->
	<link href="/modules/esup/static/css/styles.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
	<!-- Main scripts -->
	<script src="/modules/esup/static/js/scripts.js" crossorigin="anonymous"></script>
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