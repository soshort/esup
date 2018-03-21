/**
 * @file Image Paste plugin for CKEditor
 *	Uploads pasted images and files inside the editor to the server for Firefox and Chrome
 *	Feature introduced in: https://bugzilla.mozilla.org/show_bug.cgi?id=490879
 *		doesn't include images inside HTML (paste from word): https://bugzilla.mozilla.org/show_bug.cgi?id=665341
 *	Includes Drag&Drop file uploads for all the new browsers.
 *  Two toolbar buttons to perform quick upload of files.
 * Copyright (C) 2012-13 Alfonso Martínez de Lizarrondo
 *
 */

(function() {
"use strict";

// Until CKEditor provides native support for IE11 (or for those that don't udpate their CKEditor)
var isIeModern = CKEDITOR.env.gecko && CKEDITOR.env.version == 110000;

function getTimeStampId()
{
	return (new Date()).toJSON().replace(/:|T|-/g, "_").replace(/\..*/, "");
}

// Custom rule similar to the fake Object to avoid generating anything if the user tries to do something strange while a file is being uploaded
var htmlFilterRules = {
	elements: {
		$: function( element ) {
			var attributes = element.attributes,
				className = attributes && attributes[ 'class' ];

			// remove our wrappers
			if ( className == 'ImagePasteTmpWrapper' )
				return false;
		}
	}
};

// CSS that we add to the editor for our internal styling
function getEditorCss( config ) {
	return '.ImagePasteOverEditor { ' + (config.imagepaste_editorhover || 'box-shadow: 0 0 10px 1px #999999 inset !important;') + ' }' +
		'span.ImagePasteTmpWrapper { display: inline-block; position: relative; pointer-events: none;}' +
		'span.ImagePasteTmpWrapper span { top: 50%; margin-top: -0.5em; width: 100%; text-align: center; color: rgb(255, 255, 255); ' +
		'text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); font-size: 50px; font-family: Calibri,Arial,Sans-serif; pointer-events: none; ' +
		'position: absolute; display: inline-block;}';
}

var filePicker,
	filePickerEditor,
	filePickerForceLink;

var IEUpload_fileName,
	IEUpload_caller;

function PickAndUploadFile(editor, forImage, caller) {
	if (IEUpload_fileName)
	{
		alert("Please, wait to finish the current upload");
		return;
	}

	filePickerForceLink = !forImage;
	filePickerEditor = editor;

	if (typeof FormData == 'undefined')
	{
		// old IE
		var iframe = document.getElementById('imagePasteTarget');
		if (!iframe) {
			iframe = document.createElement('iframe');
			iframe.style.display = 'none';
			iframe.id = 'imagePasteTarget';
			document.body.appendChild(iframe);
		}
		IEUpload_caller = caller;

		var fnNumber = editor._.imagepasteFormUploadFn;
		var fnInitPicker = editor._.imagepasteFormInitFn;
		if (!fnNumber)
		{
			editor._.imagepasteFormUploadFn = fnNumber = CKEDITOR.tools.addFunction( setUrl, editor );
			editor._.imagepasteFormInitFn = fnInitPicker = CKEDITOR.tools.addFunction( function() {
				window.setTimeout(function() {
					var picker = document.getElementById('imagePasteTarget').contentWindow.document.getElementById('upload');
					picker.onchange=function() {
						var evdata = {
							name: this.value,
							url: this.form.action,
							context : IEUpload_caller
						};
						var result = filePickerEditor.fire('imagepaste.startUpload', evdata );

						// in v3 cancel() returns true and in v4 returns false
						// if not canceled it's the evdata object
						if ( typeof result == 'boolean' )
							return;

						IEUpload_fileName = this.value;
						this.form.action = evdata.url;
						this.form.submit();
					};
					picker.click();
				}, 100);
			}, editor);

			editor.on( 'destroy', function () {
				CKEDITOR.tools.removeFunction( this._.imagepasteFormUploadFn );
				CKEDITOR.tools.removeFunction( this._.imagepasteFormInitFn );
			} );
		}

		var form = "<form method='post' enctype='multipart/form-data' action='" + getUploadUrl(editor, fnNumber, forImage) + "'>" +
		"<input type='file' name='upload' id='upload'></form>";
		var src= 'document.open(); document.write("' + form + '");document.close();' +
				 'window.parent.CKEDITOR.tools.callFunction(' + fnInitPicker + ');';

		iframe.src = 'javascript:void(function(){' + encodeURIComponent( src ) + '}())';

		// Detect when the file upload ends to check for errors
		iframe.onreadystatechange = function() {
			if (iframe.readyState == "complete") {
				window.setTimeout(function() {
					if (IEUpload_fileName)
					{
						alert("The file upload has failed");
						IEUpload_fileName=null;
					}
				}, 100);
			}
		};

		filePicker = null;
		return;
	}

	if (!filePicker)
	{
		filePicker=document.createElement("input");
		filePicker.type='file';
		filePicker.style.overflow='hidden';
		filePicker.style.width='1px';
		filePicker.style.height='1px';
		filePicker.style.opacity=0.1;

		document.body.appendChild(filePicker);
		filePicker.addEventListener("change", function () {
				var file = filePicker.files[0],
					id = getTimeStampId(),
					fileName = file.name,
					element = createPreview(file, id, fileName, filePickerEditor, filePickerForceLink),
					data = {
						context : caller,
						element : element,
						name : fileName
					};

				// Upload the file
				if (uploadFile( filePickerEditor, file, id, filePickerForceLink, data ))
					filePickerEditor.insertElement(data.element);
			}
		);
		if (window.opera)
		{
			setTimeout(function() { filePicker.click();}, 100);
			return;
		}
	}

	filePicker.value='';
	filePicker.click();
}

	function getUploadUrl(editor, functionNumber, forImage) {
		var params = {};
		params.CKEditor = editor.name;
		params.CKEditorFuncNum = functionNumber;
		params.langCode = editor.langCode;

		var url = forImage ? editor.config.filebrowserImageUploadUrl : editor.config.filebrowserUploadUrl;
		return addQueryString( url, params );
	}

	function setUrl( fileUrl, data )
	{
		// The "data" argument may be used to pass the error message to the editor.
		if ( typeof data == 'string' && data && !fileUrl)
			alert( data );

		filePickerEditor.fire('imagepaste.endUpload', { name: IEUpload_fileName, ok: (!!fileUrl) } );

		if ( fileUrl ) {
			var element,
				attribute;
			if (filePickerForceLink)
			{
				element = new CKEDITOR.dom.element( 'a' );
				element.setText( fileUrl.match(/\/([^\/]+)$/)[1] );
				attribute = 'href';
			}
			else
			{
				element = new CKEDITOR.dom.element( 'img' );
				attribute = 'src';

				element.on('load', function(e) {
					e.removeListener();
					element.removeListener('error', errorListener);

					element.setAttribute("width", element.$.width);
					element.setAttribute("height", element.$.height);

					editor.fire('imagepaste.finishedUpload', { name: IEUpload_fileName, element: element } );
				});

				element.on('error', errorListener, null, element);
			}
			element.setAttribute(attribute, fileUrl);
			element.data( 'cke-saved-' + attribute, fileUrl);
			filePickerEditor.insertElement(element);

			if (filePickerForceLink)
				editor.fire('imagepaste.finishedUpload', { name: IEUpload_fileName, element: element } );
		}

		IEUpload_fileName = null;
	}

	/*
	 * Adds (additional) arguments to given url.
	 *
	 * @param {String}
	 *            url The url.
	 * @param {Object}
	 *            params Additional parameters.
	 */
	function addQueryString( url, params )
	{
		var queryString = [];

		if ( !params )
			return url;
		else
		{
			for ( var i in params )
				queryString.push( i + "=" + encodeURIComponent( params[ i ] ) );
		}

		return url + ( ( url.indexOf( "?" ) != -1 ) ? "&" : "?" ) + queryString.join( "&" );
	}


var addFileCmd = {
	exec: function( editor ) {
		PickAndUploadFile(editor, false, this);
	}
};

var addImageCmd = {
	exec: function( editor ) {
		PickAndUploadFile(editor, true, this);
	}
};


function hasFiles(e)
{
	var ev = e.data.$,
		data = ev.dataTransfer;

	if (!data || !data.types)
		return false;

	if (data.types.contains && data.types.contains('Files') && (!data.types.contains("text/html")) ) return true;
	if (data.types.indexOf && data.types.indexOf( 'Files' )!=-1) return true;
	return false;
}

CKEDITOR.plugins.add( 'imagepaste',
{
	//lang : 'en,es',		 v4 style for builder not compatible with v3
	lang : ['en','es'],
	icons: 'addfile,addimage', // %REMOVE_LINE_CORE%

	onLoad : function(e)
	{
		// v4
		// In v4 this setting is global for all instances:
		if (CKEDITOR.addCss)
			CKEDITOR.addCss( getEditorCss(CKEDITOR.config) );

		// CSS for container
		var node = CKEDITOR.document.getHead().append( 'style' );
		node.setAttribute( 'type', 'text/css' );
		var content = '.ImagePasteOverContainer { ' + (CKEDITOR.config.imagepaste_containerhover || 'box-shadow: 0 0 10px 1px #99DD99 !important;') + ' }';

		if ( CKEDITOR.env.ie )
			node.$.styleSheet.cssText = content;
		else
			node.$.innerHTML = content;

	},

	init : function( editor )
	{
		var imageExtensions = editor.config.imagepaste_imageExtensions || "jpe?g|gif|png";
		editor.config.imagepaste_imageRegexp = new RegExp( "\.(?:" + imageExtensions + ")$", "i");

		// v3
		if (editor.addCss)
			editor.addCss( getEditorCss(editor.config) );

		// if not defined specifically for images, reuse the default file upload url
		if (!editor.config.filebrowserImageUploadUrl)
			editor.config.filebrowserImageUploadUrl = editor.config.filebrowserUploadUrl;

		if (!editor.config.filebrowserUploadUrl && !editor.config.filebrowserImageUploadUrl)
		{
			if (window.console && console.log)
			{
				console.log("The editor is missing the 'config.filebrowserUploadUrl' entry to know the url that will handle uploaded files.");
				console.log("It should handle the posted file as shown in Example 3: http://docs.cksource.com/CKEditor_3.x/Developers_Guide/File_Browser_%28Uploader%29/Custom_File_Browser#Example_3");
				console.log("More info: http://alfonsoml.blogspot.com/2009/12/using-your-own-uploader-in-ckeditor.html");
				console.log("The imagePaste plugin now is disabled.");
			}
			return;
		}

		// Add a listener to check file size and valid extensions
		editor.on( 'imagepaste.startUpload' , function(ev)
		{
			var file = ev.data && ev.data.file;
			if (editor.config.imagepaste_maxFileSize &&
				file && file.size &&
				file.size > editor.config.imagepaste_maxFileSize )
			{
				alert( editor.lang.imagepaste.fileTooBig );
				ev.cancel();
			}
			var name = ev.data.name;
			if (editor.config.imagepaste_invalidExtensions)
			{
				var re = new RegExp( "\.(?:" + editor.config.imagepaste_invalidExtensions + ")$", "i");
				if ( re.test( name ) )
				{
					alert( editor.lang.imagepaste.invalidExtension );
					ev.cancel();
				}
			}
			if (editor.config.imagepaste_acceptedExtensions)
			{
				var re = new RegExp( "\.(?:" + editor.config.imagepaste_acceptedExtensions + ")$", "i");
				if ( !re.test( name ) )
				{
					alert( editor.lang.imagepaste.nonAcceptedExtension.replace("%0", editor.config.imagepaste_acceptedExtensions) );
					ev.cancel();
				}
			}

		});

		// Paste from clipboard:
		editor.on( 'paste', function(e) {
			var data = e.data,
				html = (data.html || ( data.type && data.type=='html' && data.dataValue));

			if (!html)
				return;

			// strip out webkit-fake-url as they are useless:
			if (CKEDITOR.env.webkit && (html.indexOf("webkit-fake-url")>0) )
			{
				alert("Sorry, the images pasted with Safari aren't usable");
				window.open("https://bugs.webkit.org/show_bug.cgi?id=49141");
				html = html.replace( /<img src="webkit-fake-url:.*?">/g, "");
			}

			// Handles image pasting in Firefox
			// Replace data: images in Firefox and upload them.
			// No longer required with Firefox 22
			html = html.replace( /<img src="data:image\/.{3,4};base64,.*?" alt="">/g, function( img )
				{
					if (!editor.config.filebrowserImageUploadUrl)
						return "";

					var match = img.match(/"(data:image\/(.{3,4});base64,.*?)"/),
						data = match[1],
						type = match[2].toLowerCase(),
						id = getTimeStampId();

					if (type=='jpeg')
						type='jpg';

					var fileName = id + '.' + type,
						uploadData = {
							context : 'pastedimage',
							name : fileName
						};

					var xhr = createXHRupload(editor, id, false, uploadData);
					if (!xhr)
						return "";

					// Create the multipart data upload.
					var bin = window.atob( data.split(',')[1] ),
						BOUNDARY = "---------------------------1966284435497298061834782736",
						rn = "\r\n",
						req = "--" + BOUNDARY;

					req += rn + "Content-Disposition: form-data; name=\"upload\"";
					// add timestamp?
					req += "; filename=\"" + uploadData.name + "\"" + rn + "Content-type: image/" + type;
					req += rn + rn + bin + rn + "--" + BOUNDARY + "--";

					xhr.setRequestHeader("Content-Type", "multipart/form-data; boundary=" + BOUNDARY);
					xhr.sendAsBinary(req);

					var svg = createSVGAnimation( data, id, editor );

					// fight against v4.1
					if (editor.filter) {
						editor.on( 'afterPaste' , function( ev ) {
							// only once
							ev.removeListener();

							var text = editor.document.$.getElementById("text" + id);

							if (!text.nextSibling)
								text.parentNode.appendChild(svg.$.lastChild);
						} );
					}

					return svg.getOuterHtml();
				});

			if (e.data.html)
				e.data.html = html;
			else
				e.data.dataValue = html;
		});

		var avoidBadUndo = function(e) {
			if (editor.mode != "wysiwyg")
				return;

			var root = editor.document;
			if (editor.editable)
				root = editor.editable();

			// detect now if the contents include our tmp node
			if (root.$.querySelector( '.ImagePasteTmpWrapper') )
			{
				var move = e.name.substr(5).toLowerCase();

				// If the user tried to redo but there are no more saved images forward and this is a bad image, move back instead.
				if ( move=='redo' && editor.getCommand(move).state == CKEDITOR.TRISTATE_DISABLED )
					move = 'undo';

				// Move one extra step back/forward
				editor.execCommand( move );
			}
		};
		// on dev mode plugins might not load in the right order with empty cache
		var cmd = editor.getCommand('undo');
		cmd && cmd.on('afterUndo', avoidBadUndo );
		cmd = editor.getCommand('redo');
		cmd && editor.getCommand('redo').on('afterRedo', avoidBadUndo );
		// http://dev.ckeditor.com/ticket/10101
		editor.on('afterUndo', avoidBadUndo );
		editor.on('afterRedo', avoidBadUndo );

		// Buttons to launch the file picker easily
		// Files
		editor.addCommand( 'addFile', addFileCmd);

		editor.ui.addButton( 'addFile', {
			label: editor.lang.imagepaste.addFile,
			command: 'addFile',
			icon : this.path + 'icons/addfile.png', // %REMOVE_LINE_CORE%
			toolbar: 'insert',
			allowedContent : 'a[!href];span[id](ImagePasteTmpWrapper);',
			requiredContent : 'a[!href]'
		});

		// Images
		editor.addCommand( 'addImage', addImageCmd);

		editor.ui.addButton && editor.ui.addButton( 'addImage', {
			label: editor.lang.imagepaste.addImage,
			command: 'addImage',
			icon : this.path + 'icons/addimage.png', // %REMOVE_LINE_CORE%
			toolbar: 'insert',
			allowedContent : 'img[!src,width,height];span[id](ImagePasteTmpWrapper);',
			requiredContent : 'img[!src]'
		});


		if (typeof FormData == 'undefined')
			return;

		var root,
			visibleRoot;
		var minX=-1, minY, maxX, maxY;
		// Hint in the main document
		var mainMinX=-1, mainMinY, mainMaxX, mainMaxY;

		CKEDITOR.on( 'imagepaste.droppedFile', function() {
			editor.container.removeClass( 'ImagePasteOverContainer' );
		});

		CKEDITOR.document.on( 'dragenter', function(e) {
			if (mainMinX == -1)
			{
				if (!hasFiles(e))
					return;

				// Opera doesn't show it on the html (always) or if the body has background color  CORE-40217
				if (!editor.readOnly)
					editor.container.addClass( 'ImagePasteOverContainer' );

				mainMinX=0;
				mainMinY=0;
				mainMaxX=CKEDITOR.document.$.body.parentNode.clientWidth;
				mainMaxY=CKEDITOR.document.$.body.parentNode.clientHeight;
			}
		});
		CKEDITOR.document.on( 'dragleave', function(e) {
			if ( mainMinX == -1 )
				return;

			var ev = e.data.$;
			if ((ev.clientX<=mainMinX) || (ev.clientY<=mainMinY) || (ev.clientX>=mainMaxX) || (ev.clientY>=mainMaxY))
			{
				editor.container.removeClass( 'ImagePasteOverContainer' );
				mainMinX = -1;
			}
		});

		var rootDropListener = function(e) {
			// editor
			visibleRoot.removeClass( 'ImagePasteOverEditor' );
			minX = -1;

			//container
			// We fire an event on CKEDITOR so all the instances get notified and remove their class
			// This is an "internal" event to the plugin
			CKEDITOR.fire( 'imagepaste.droppedFile' );
			mainMinX = -1;

			if (editor.readOnly)
			{
				e.data.preventDefault();
				return false;
			}

			var ev = e.data.$,
				data = ev.dataTransfer;
			if ( data && data.files && data.files.length>0 )
			{
				// Create Undo image
				editor.fire( 'saveSnapshot' );

				for( var i=0; i<data.files.length; i++)
				{
					var file = data.files[ i ],
						id = CKEDITOR.tools.getNextId(),
						fileName = file.name,
						range,
						element = createPreview(file, id, fileName, editor);

					// Prevent default insertion
					e.data.preventDefault();

					var evdata = {
						context : ev,
						element : element,
						name : fileName
					};

					if (!uploadFile( editor, file, id, false, evdata ))
						continue;

					element = evdata.element;
					// if we're adding several links, add a space between them
					if ( range && element.getName()=='a' )
					{
						if ( range.pasteHTML )
							range.pasteHTML( '&nbsp;' ); // simple space doesn't work
						else
							range.insertNode( editor.document.$.createTextNode( ' ' ) );
					}

					// Move to insertion point
					// Firefox, custom properties in event. They might add the new W3C api for Fx 10
					if ( ev.rangeParent )
					{
						if (!range)
						{
							var node = ev.rangeParent,
								offset = ev.rangeOffset;
							range = editor.document.$.createRange();
							range.setStart( node, offset );
							range.collapse( true );
						}
						range.insertNode( element.$ );
					}
					else
					{
						// Webkit, old documentView API
						if ( document.caretRangeFromPoint )
						{
							if (!range)
							{
								range = editor.document.$.caretRangeFromPoint( ev.clientX, ev.clientY );
							}
							range.insertNode( element.$ );
						}
						else
						{
							// IE (10), still doesn't support new API
							if ( document.body.createTextRange )
							{
								if (!range)
								{
									range = editor.document.$.body.createTextRange();
									range.moveToPoint( ev.clientX, ev.clientY );
								}
								range.pasteHTML( element.$.outerHTML );
							}
							else
							{
								// Opera comes here :-(
								// let's just insert it at the current location.
								editor.insertElement( element );
							}
						}
					}

					editor.fire( 'saveSnapshot' );
				}
			}
		};

		var rootPasteListener = function(e) {
			// IE11 uses window.clipboardData
			var data = e.data.$.clipboardData || window.clipboardData;
			if (!data)
				return;

			// Chrome has clipboardData.items. Other browsers don't provide this info at the moment.
			// Firefox implements clipboardData.files in 22
			var items = data.items || data.files;
			if (!items)
				return;

			if (items && items.length>0)
			{
				var i,
					item;

				// Check first if there is a text/html or text/plain version, and leave the browser use that:
				// otherwise, pasting from MS Word to Chrome in Mac will always generate a black rectangle.
				if (items[0].kind)
				{
					for (i=0; i< items.length; i++)
					{
						item = items[i];
						if ( item.kind=="string" && (item.type=="text/html" || item.type=="text/plain") )
							return;
					}
				}

				// We're safe, stupid Office-Mac combination won't disturb us.
				for (i=0; i< items.length; i++)
				{
					item = items[i];
					if ( item.kind && item.kind != "file" )
						continue;

					e.data.preventDefault();

					var file = (item.getAsFile ? item.getAsFile() : item),
						id = getTimeStampId(),
						fileName = file.name || (id + '.png'),
						element = createPreview(file, id, fileName, editor),
						evData = {
							context : e.data.$,
							element : element,
							name : fileName
						};

					// Upload the file
					if (!uploadFile( editor, file, id, false, evData ))
						continue;

					element = evData.element;

					// Insert in the correct position after the pastebin has been removed
					editor.document.getBody().append( element );

					window.setTimeout( function() {
						editor.fire( 'updateSnapshot' );
						editor.insertElement(element);
						editor.fire( 'updateSnapshot' );
					}, 0);
				}
			}
		};

		var rootDragEnter = function(e) {
			if (minX == -1)
			{
				if (!hasFiles(e))
					return;

				// Opera doesn't show it on the html (always) or if the body has background color  CORE-40217
				if (!editor.readOnly)
					visibleRoot.addClass( 'ImagePasteOverEditor' );

				var rect = visibleRoot.$.getBoundingClientRect();
				minX=rect.left;
				minY=rect.top;
				maxX=minX + visibleRoot.$.clientWidth;
				maxY=minY + visibleRoot.$.clientHeight;
			}
		};

		var rootDragLeave = function(e) {
			if ( minX == -1 )
				return;

			var ev = e.data.$;

			if ((ev.clientX<=minX) || (ev.clientY<=minY) || (ev.clientX>=maxX) || (ev.clientY>=maxY))
			{
				visibleRoot.removeClass( 'ImagePasteOverEditor' );
				minX = -1;
			}
		};

		var rootDragOver = function(e) {
			if (minX != -1 )
			{
				if (editor.readOnly)
				{
					e.data.$.dataTransfer.dropEffect = 'none';
					e.data.preventDefault();
					return false;
				}

				// Show Copy instead of Move. Works for Chrome
				// Opera shows Copy by default
				// Firefox and IE10 don't respect this change (Firefox by default doesn't enter here)
				// https://bugzilla.mozilla.org/show_bug.cgi?id=484511
				e.data.$.dataTransfer.dropEffect = 'copy';

				if (CKEDITOR.env.ie || isIeModern)
					e.data.preventDefault();
			}
		};

		// drag & drop, paste
		editor.on( 'contentDom', function(ev) {
			root = editor.document;
			visibleRoot = root.getBody().getParent();

			// v4 inline editing
			// ELEMENT_MODE_INLINE
			if (editor.elementMode == 3 )
			{
				root = editor.element;
				visibleRoot = root;
			}
			// v4 divArea
			if ( editor.elementMode == 1 && 'divarea' in editor.plugins )
			{
				root = editor.editable();
				visibleRoot = root;
			}

			root.on( 'paste', rootPasteListener);

			root.on( 'dragenter', rootDragEnter);

			root.on( 'dragleave', rootDragLeave);

			// https://bugs.webkit.org/show_bug.cgi?id=57185
			if ( !CKEDITOR.env.gecko || isIeModern )
			{
				root.on( 'dragover', rootDragOver);
			}

			// Must use CKEditor 3.6.3 for IE 10
			root.on( 'drop', rootDropListener);
		});

		editor.on( 'contentDomUnload', function(ev) {
			if (!root || !root.removeListener)
				return;

			root.removeListener( 'paste', rootPasteListener);
			root.removeListener( 'dragenter', rootDragEnter);
			root.removeListener( 'dragleave', rootDragLeave);
			root.removeListener( 'dragover', rootDragOver);
			root.removeListener( 'drop', rootDropListener);

			root = null;
			visibleRoot = null;
		});

		// v 4.1 filters
		if (editor.addFeature)
		{
			editor.addFeature( {
				allowedContent: 'img[!src,width,height];a[!href];span[id](ImagePasteTmpWrapper);'
			} );
		}

	}, //Init

	afterInit: function( editor ) {
		var dataProcessor = editor.dataProcessor,
			htmlFilter = dataProcessor && dataProcessor.htmlFilter;

		if ( htmlFilter )
			htmlFilter.addRules( htmlFilterRules );
	}

} );

// Creates the element, but doesn't insert it
function createPreview(file, id, fileName, editor, forceLink)
{
	var isImage = editor.config.imagepaste_imageRegexp.test( fileName ),
		element;
	// Create and insert our element
	if ( !forceLink && isImage )
	{
		element = createSVGAnimation(file, id, editor);
	}
	else
	{
		element = new CKEDITOR.dom.element( 'a' );
		element.setText( fileName );
		element.setAttribute( 'id', id );
		element.setAttribute( 'class', 'ImagePasteTmpWrapper');
	}

	return element;
}

function errorListener(e) {
	alert("Failed to load the image with the provided URL: '" + e.sender.data( 'cke-saved-src') + "'");
	e.listenerData.remove();
}

// Sets up a XHR object to handle the upload
function createXHRupload(editor, id, forceLink, data)
{
	var xhr = new XMLHttpRequest(),
		isImage = editor.config.imagepaste_imageRegexp.test( data.name ),
		attribute,
		target = xhr.upload,
		forImage;

	if ( !forceLink && isImage )
	{
		attribute = 'src';
		forImage=true;
	}
	else
	{
		attribute = 'href';
		forImage=false;
	}

	// nice progress effect. Opera used to lack xhr.upload
	if ( target )
	{
		target.onprogress = function( evt )
		{
			updateProgress(editor, id, evt);
		};
	}

	data.url = getUploadUrl(editor, 2, forImage);

	var result = editor.fire('imagepaste.startUpload', data);
	// in v3 cancel() returns true and in v4 returns false
	// if not canceled it's the data object, so let's use that.
	if ( typeof result == 'boolean' )
		return null;

	// Upload the file
	xhr.open("POST", data.url );
	xhr.onload = function() {
		// Upon finish, get the url and update the file
		var parts = xhr.responseText.match(/2,\s*("|')(.*?[^\\]?)\1(?:,\s*\1(.*?[^\\]?)\1)?\s*\)/),
			fileUrl = parts && parts[2],
			msg = parts && parts[3],
			el = editor.document.getById( id );

		// final update
		updateProgress(editor, id, null);
		// Correct the Undo image
		editor.fire( 'updateSnapshot' );

		editor.fire('imagepaste.endUpload', { name: data.name, ok: (!!fileUrl) } );
		if (xhr.status!=200)
		{
			if (xhr.status == 413)
				alert( editor.lang.imagepaste.fileTooBig );
			else
				alert('Error posting the file to ' + data.url + '\r\nResponse status: ' + xhr.status);

			if (window.console)
				console.log(xhr);
		}
		else
		{
			if (!parts)
			{
				msg = 'Error posting the file to ' + data.url + '\r\nInvalid data returned (check console)';
				if (window.console)
					console.log(xhr.responseText);
			}
		}
		// If the element doesn't exists it means that the user has deleted it or pressed undo while uploading
		// so let's get out
		if (!el)
			return;

		if ( fileUrl )
		{
			fileUrl = fileUrl.replace(/\\'/g, "'");
			if (el.$.nodeName == 'SPAN')
			{
				// create the final img, getting rid of the fake div
				var img = new CKEDITOR.dom.element( 'img', editor.document );
				img.data( 'cke-saved-' + attribute, fileUrl);
				img.setAttribute( attribute, fileUrl);

				// in case the user tries to get the html right now, a little protection
				el.data('cke-real-element-type', 'img');
				el.data('cke-realelement', encodeURIComponent( img.getOuterHtml() ));
				el.data('cke-real-node-type', CKEDITOR.NODE_ELEMENT);

				// wait to replace until the image is loaded to prevent flickering
				img.on('load', function(e) {
					e.removeListener();
					img.removeListener( 'error', errorListener);

					img.replace( el );
					img.setAttribute("width", img.$.width);
					img.setAttribute("height", img.$.height);

					editor.fire('imagepaste.finishedUpload', { name: data.name, element: img } );

					// Correct the Undo image
					editor.fire( 'updateSnapshot' );
				});

				img.on('error', errorListener, null, el);

				return;
			}
			el.data( 'cke-saved-' + attribute, fileUrl);
			el.setAttribute( attribute, fileUrl);
			el.removeAttribute( 'id' );
			el.removeAttribute( 'class' );

			editor.fire('imagepaste.finishedUpload', { name: data.name, element: el } );
		}
		else
		{
			el.remove();
			if (msg)
				alert( msg );
		}
		// Correct undo image
		editor.fire( 'updateSnapshot' );
	};
	xhr.onerror = function(e) {
		alert('Error posting the file to ' + data.url );
		if (window.console)
			console.log(e);

		var el = editor.document.getById( id );
		if (el)
			el.remove();
	}

	// CORS https://developer.mozilla.org/en-US/docs/HTTP/Access_control_CORS
	xhr.withCredentials = true;

	return xhr;
}

// Takes care of uploading the file object using XHR
function uploadFile(editor, file, id, forceLink, data)
{
	data.file = file;
	var xhr = createXHRupload(editor, id, forceLink, data);

	if (!xhr)
		return false;

	var formdata = new FormData();
	formdata.append( 'upload', file, data.name );
	xhr.send( formdata );
	return true;
}

function updateProgress(editor, id, evt)
{
	if (!editor.document || !editor.document.$)
		return;

	var doc = editor.document.$,
		rect = doc.getElementById("rect" + id),
		text = doc.getElementById("text" + id),
		value, textValue;

	if ( evt )
	{
		if ( !evt.lengthComputable )
			return;

		value = (100*evt.loaded/evt.total).toFixed(2) + "%";
		textValue = (100*evt.loaded/evt.total).toFixed() + "%";
	}
	else
	{
		textValue = value = editor.lang.imagepaste.processing;
		value = 100;
/*
		if (text)
		{
			text.parentNode.removeChild(text);
			text = null;
		}
*/
	}
	if (rect)
		rect.setAttribute("width", value);
	if (text)
		text.firstChild.nodeValue = textValue;
}

// Show a grayscale version of the image that animates toward the full color version
function createSVGAnimation( file, id, editor )
{
	var element = new CKEDITOR.dom.element( 'span' ),
		div = element.$,
		theURL,
		doc = editor.document.$,
		span = doc.createElement('span');

	element.setAttribute( 'id', id );
	element.setAttribute( 'class', 'ImagePasteTmpWrapper');
	span.setAttribute( 'id' , 'text' + id);
	span.appendChild( doc.createTextNode('0 %'));
	div.appendChild(span);

	if (typeof file != "string")
	{
		theURL = window.URL || window.webkitURL;
		if ( !theURL || !theURL.revokeObjectURL)
			return element;
	}

	var svg = doc.createElementNS("http://www.w3.org/2000/svg", "svg");
	svg.setAttribute( 'id' , 'svg' + id);

	// just to find out the image dimensions as they are needed for the svg block
	var img = doc.createElement( 'img' );
	if (theURL)
	{
		img.onload = function(e) {
			if (this.onload)
			{
				theURL.revokeObjectURL( this.src );
				this.onload = null;
			}

			// in IE it's inserted with the HTML, so we can't reuse the svg object
			var svg = doc.getElementById('svg' + id);
			svg.setAttribute("width", this.width + 'px');
			svg.setAttribute("height", this.height + 'px');
			// Chrome
			doc.getElementById(id).style.width = this.width + 'px';
		};
		img.src = theURL.createObjectURL( file );
	}
	else
	{
		// base64 data, dimensions are available right now in Firefox
		img.src = file;
		// extra protection
		img.onload = function(e) {
			this.onload = null;

			// we're pasting so it's inserted with the HTML, so we can't reuse the svg object
			var svg = doc.getElementById('svg' + id);
			if (svg)
			{
				svg.setAttribute("width", this.width + 'px');
				svg.setAttribute("height", this.height + 'px');
			}
		};
		svg.setAttribute("width", img.width + 'px');
		svg.setAttribute("height", img.height + 'px');
	}

	div.appendChild(svg);

	var filter = doc.createElementNS("http://www.w3.org/2000/svg", "filter");
	filter.setAttribute("id", "SVGdesaturate");
	svg.appendChild(filter);

	var feColorMatrix = doc.createElementNS("http://www.w3.org/2000/svg", "feColorMatrix");
	feColorMatrix.setAttribute("type", "saturate");
	feColorMatrix.setAttribute("values", "0");
	filter.appendChild(feColorMatrix);

	var clipPath = doc.createElementNS("http://www.w3.org/2000/svg", "clipPath");
	clipPath.setAttribute("id", "SVGprogress" + id);
	svg.appendChild(clipPath);

	var rect = doc.createElementNS("http://www.w3.org/2000/svg", "rect");
	rect.setAttribute("id", "rect" + id);
	rect.setAttribute("width", "0");
	rect.setAttribute("height", "100%");
	clipPath.appendChild(rect);

	var image = doc.createElementNS("http://www.w3.org/2000/svg", "image");
	image.setAttribute("width", "100%");
	image.setAttribute("height", "100%");

	if (theURL)
	{
		image.setAttributeNS('http://www.w3.org/1999/xlink','href', theURL.createObjectURL( file ));
		var loaded = function( e ) {
			theURL.revokeObjectURL( image.getAttributeNS('http://www.w3.org/1999/xlink','href') );
			image.removeEventListener( "load", loaded, false);
		};
		image.addEventListener( "load", loaded, false);
	}
	else
		image.setAttributeNS('http://www.w3.org/1999/xlink','href', file );

	var image2 = image.cloneNode(true);
	image.setAttribute("filter", "url(#SVGdesaturate)");
	image.style.opacity="0.5";

	svg.appendChild(image);

	image2.setAttribute("clip-path", "url(#SVGprogress" + id + ")");
	svg.appendChild(image2);

	return element;
}


/*
	Compatibility between CKEditor 3 and 4
*/
if (CKEDITOR.skins)
{
	CKEDITOR.plugins.setLang = CKEDITOR.tools.override( CKEDITOR.plugins.setLang , function( originalFunction )
	{
		return function( plugin, lang, obj )
		{
			if (plugin != "devtools" && typeof obj[plugin] != 'object')
			{
				var newObj = {};
				newObj[ plugin ] = obj;
				obj = newObj;
			}
			originalFunction.call(this, plugin, lang, obj);
		};
	});
}

})();


/**
 * Fired when file starts being uploaded by the "imagepaste" plugin
 * @name CKEDITOR.editor#imagepaste.startUpload
 * @event
 * @param {String} [name] The file name.
 * @param {String} [url] The url that will be used for the upload. It can be modified to your needs on each upload.
 * @param {String|Object} [context] Context that caused the upload (a string if it's a pasted image, a DOM event for drag&drop and copied files, the toolbar button for those cases)
 * @param {Object} [file] The file itself (if available).
 */

/**
 * Fired when file upload ends on the "imagepaste" plugin
 * @name CKEDITOR.editor#imagepaste.endUpload
 * @event
 * @param {String} [name] The file name.
 * @param {Boolean} [ok] Whether the file has been correctly uploaded or not
 */

/**
 * Fired when the final element has been inserted by the "imagepaste" plugin (after it has been uploaded)
 * @name CKEDITOR.editor#imagepaste.finishedUpload
 * @event
 * @param {String} [name] The file name.
 * @param {CKEDITOR.dom.element} [element] The element node that has been inserted
 */
