<div class="panel panel-default">
	<div class="panel-body">
		<div style="line-height: <?php echo $height ?>rem">
			<img id="captcha" src="/captcha/default" alt="CAPTCHA Image" style="height: <?php echo $height ?>rem" onclick="document.getElementById('captcha').src = '/captcha/default?id=' + Math.random();" />
			<span class="octicon octicon-sync" onclick="document.getElementById('captcha').src = '/captcha/default?id=' + Math.random();"></span>
		</div>
	</div>
</div>