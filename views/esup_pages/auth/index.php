<div class="authorization-form">
	<div class="col-md-4 offset-md-4">
		<form role="form" action="" method="post">
			<h1 class="form-signin-heading">Авторизуйтесь</h1>
			<div class="form-group">
				<label class="col-form-label" for="exampleInputEmail1">Логин</label>
				<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Логин" name="login">
			</div>
			<div class="form-group">
				<label class="col-form-label" for="exampleInputPassword1">Пароль</label>
				<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль" name="password">
			</div>
			<div class="form-group">
				<label class="col-form-label" for="captcha1">Код с картинки</label>
				<input type="text" class="form-control" id="captcha1" placeholder="Код с картинки" name="captcha_code">
			</div>
			<?php echo View::factory('esup_pieces/captcha/default', array('height' => 4)) ?>
			<div class="form-group">
				<input name="csrf" type="hidden" value="<?php echo Security::token() ?>">
				<input type="submit" class="btn btn-primary" name="auth" value="Войти" />
			</div>
	    	<?php if (isset($error) AND $error == TRUE): ?>
	        	<div class="alert alert-danger">Неверный логин или пароль.</div>
	    	<?php endif ?>
		</form>
	</div>
</div>