<!-- elFinder CSS (REQUIRED) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/css/elfinder.full.min.css" integrity="sha256-6xgSwKeUTojt0JyIieijNPupZi96Qm78QinN4Ui5fbc=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/css/theme.min.css" integrity="sha256-EBf9xfjiYBGD7aZIDZak7QEiT4AhhQ5zVBnB7YMjDa8=" crossorigin="anonymous" />
<!-- elFinder JS (REQUIRED) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/js/elfinder.full.min.js" integrity="sha256-m0g9zzaeCitJx8DKF5NjGII4nh2gi8ohsMndb6rrepg=" crossorigin="anonymous"></script>
<!-- elFinder translation (OPTIONAL) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.29/js/i18n/elfinder.ru.min.js" integrity="sha256-CtjkVOUrvFsy3Ncx47wXC1zu6iD+TSy2VR/EMvSWviA=" crossorigin="anonymous"></script>
<h3 class="main_header">
	Файловый менеджер
</h3>
<div class="row main-list">
	<div class="col-md-12">
		<div id="elfinder"></div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#elfinder').elfinder({
			url: '/esup/fmanager/init',
			lang: 'ru',
			resizable: false,
			commandsOptions: {
				quicklook: {
					googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
				}
			}
		}).elfinder('instance');	
	});
</script>