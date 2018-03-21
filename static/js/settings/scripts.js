$(function(){
	var editors = [];
	$('textarea[data-editor]').each(function(i){
		var t = $(this);
		editors[i] = ace.edit(t.attr('id'));
		editors[i].session.setMode('ace/mode/'+t.attr('data-editor'));
	});

	$(document).on('submit', 'form', function(){
		$.each(editors, function(i, editor){
			$('input[name="'+editor.env.textarea.dataset.hiddenName+'"]').val(editor.getValue());
		});
	});
});