$(function(){
	/* Tabs behavior */
	var form_action = $('#main_form').attr('action'),
		hash = window.location.hash;
	$('form').attr('action', form_action+hash);
	$('ul.nav a[href="' + hash + '"]').tab('show');

	$(document).on('click', '.nav-tabs a', function(e){
		$(this).tab('show');
		var scrollmem = $('body').scrollTop();
		window.location.hash = this.hash;
		$('form').attr('action', form_action+this.hash);
		$('html,body').scrollTop(scrollmem);
	});

	/* JQuery UI Calendar init */
	$('.datepicker').datepicker({
		dateFormat: 'dd.mm.yy',
	    onSelect: function(datetext, inst){
	    	if ($(inst.input).is('.with-time')) {
		        var d = new Date(),
		        	h = (d.getHours() < 10 ? '0' : '') + d.getHours(),
		        	m = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes(),
		        	s = (d.getSeconds() < 10 ? '0' : '') + d.getSeconds();
		        datetext = datetext + " " + h + ":" + m + ":" + s;
		        $(this).val(datetext);
	    	}
	    },
	}, $.datepicker.regional['ru']);

	/* CKEditor init */
	CKEDITOR.plugins.addExternal('imagepaste', '/modules/esup/static/js/lib/ckeditor/custom/plugins/imagepaste/', 'plugin.js');
	CKEDITOR.plugins.addExternal('youtubebootstrap', '/modules/esup/static/js/lib/ckeditor/custom/plugins/youtubebootstrap/', 'plugin.js');

	$('textarea.ckeditor-area').each(function(){
		CKEDITOR.replace('form_'+$(this).attr('name'), {
		    extraPlugins: 'imagepaste,youtubebootstrap',
		    filebrowserUploadUrl: '/esup/ckfileuploader',
		    filebrowserBrowseUrl: '/esup/fmanager/windowed?type=Files',
			allowedContent: true
		});
	});

	/* Fancybox and Cropper init */
	$('[data-fancybox]').fancybox({
		hash: false,
		loop: true,
		keyboard: true,
		animationEffect: false,
		arrows: true,
		clickContent: false,
		afterShow: function(inst, slide) {
			var input = slide.opts.$orig.next(),
				aspectRatioWidth = parseInt(input.attr('data-ar-width'), 10),
				aspectRatioHeight = parseInt(input.attr('data-ar-height'), 10),
				cropper = new Cropper(slide.$image[0], {
				aspectRatio: aspectRatioWidth / aspectRatioHeight,
				zoomable: false,
				ready: function() {
					if (input.val() != '') {
						cropper.setData(JSON.parse(input.val()));
					}
				},
				cropend: function(event) {
					input.val(JSON.stringify(cropper.getData()));
				}
			});
		}
	});

	/* Init form fields with yandex maps */
	if (typeof ymaps !== 'undefined') {
		ymaps.ready(function(){
		    $('div[data-yandex-maps-key]').each(function(){
		        var t = $(this),
		        	input = t.next(),
		        	key = t.attr('data-yandex-maps-key'),
		        	/*mapId = 'map_' + mapKey,*/
		            lat = t.attr('data-lat'),
		            lng = t.attr('data-lng'),
		            zoom = t.attr('data-zoom'),
		            coordinates = lat + ',' + lng,
		            map = new ymaps.Map(t.attr('id'), {
		                center: [lat, lng],
		                zoom: zoom
		            }),
		        	place = new ymaps.Placemark([lat, lng], {
		            	hintContent: 'Укажите местоположение нового места'
			        }, {
			            draggable: true
			        });
		        map.events.add('boundschange', function(e){
		            input.val(coordinates + ':' + map.getZoom());
		        });
		        map.controls.add('zoomControl', {
		            left: '20',
		            top: '20'
		        });
		        map.behaviors.enable('scrollZoom');
		        place.events.add('dragend', function(e) {
		            coordinates = e.get('target').geometry.getCoordinates();
		            input.val(coordinates + ':' + map.getZoom());
		        });
		        map.geoObjects.add(place);
		    });
		})
	}
});