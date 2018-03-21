$(function(){
	$('.trigger_xml').click(function(){
		$(this).parent().next().slideToggle('fast');
	});
	return false;
});