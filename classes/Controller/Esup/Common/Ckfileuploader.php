<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Ckfileuploader extends Controller_Esup_Common {

	public function action_index() {
		$CKEditor = $_GET['CKEditor'] ;
		$funcNum = $_GET['CKEditorFuncNum'] ;
		$langCode = $_GET['langCode'] ;
		$url = '' ;
		$path = DOCROOT.'/static/uploads/uploaded_files';

		// Optional message to show to the user (file renamed, invalid file, not authenticated...)
		$message = '';

		// In FCKeditor the uploaded file was sent as 'NewFile' but in CKEditor is 'upload'
		if (isset($_FILES['upload'])) {
			/*if (!Cookie::get('admin', NULL)) {
				$message = 'Permission denied for this action';
			} else {*/
				$ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
				$hash = md5($_FILES['upload']['tmp_name'].time());
				$file_name = $hash.'.'.$ext;
				$full_name = Upload::save($_FILES['upload'], $file_name, $path);
				echo $full_name;
			    $url = '/static/uploads/uploaded_files/'.$file_name ;
			/*}*/
		}
		else {
		    $message = 'No file has been sent';
		}
		// ------------------------
		// Write output
		// ------------------------
		// We are in an iframe, so we must talk to the object in window.parent
		die('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>');
	}

}
