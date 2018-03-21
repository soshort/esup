$(function(){
	$('.lang_active').change(function(){
		$.post('/esup/languages/active', { id: $(this).attr('id'), value: $(this).prop('checked') }, function(data){
			if (data.status == 'error') {
				console.log(data.message);
			}
		}, 'JSON');
	});
});