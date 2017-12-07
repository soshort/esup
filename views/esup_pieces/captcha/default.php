<div class="panel panel-default">
	<div class="panel-body">
		<div style="line-height: <?php echo $height ?>px">
			<img id="captcha" src="/captcha/default" alt="CAPTCHA Image" style="height: <?php echo $height ?>px" onclick="document.getElementById('captcha').src = '/captcha/default?id=' + Math.random();" />
			<span class="glyphicon glyphicon-refresh" onclick="document.getElementById('captcha').src = '/captcha/default?id=' + Math.random();"></span>
		</div>
	</div>
</div>