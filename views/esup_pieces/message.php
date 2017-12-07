<?php if ($flash = $session->get_once('flash')): ?>
	<input type="hidden" class="notification" data-status="<?php echo $flash['status'] ?>" data-message="<?php echo $flash['message'] ?>">
	<script type="text/javascript">
		$(function(){
			notification($('.notification').attr('data-status'), $('.notification').attr('data-message'));
		});
	</script>
<?php endif ?>