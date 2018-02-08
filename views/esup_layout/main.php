<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" href="/modules/esup/static/favicon.png">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<!-- Css3 animations -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" integrity="sha256-j+P6EZJVrbXgwSR5Mx+eCS6FvP9Wq27MBRC/ogVriY0=" crossorigin="anonymous">
	<!-- Main styles -->
	<link href="/modules/esup/static/css/styles.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<!-- JQuery ui -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/ui.datepicker-ru.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/overcast/jquery-ui.css">
	<!-- Octicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/4.4.0/font/octicons.min.css" integrity="sha256-pNGG0948CVwfHxxS8lVkUKftaSsMBzFSUknrKr2utfY=" crossorigin="anonymous">
	<!-- Popper -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- Notifycations -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-noty/2.4.1/packaged/jquery.noty.packaged.min.js" integrity="sha256-2vEdfXRZVGvgd0uRdeWQQsMawZy0r131Vq7ZgduHwgI=" crossorigin="anonymous"></script>
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