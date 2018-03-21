<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Ckfileuploader extends Controller_Esup_Common {

	public function action_index()
	{
		$CKEditor = $_GET['CKEditor'] ;
		$funcNum = $_GET['CKEditorFuncNum'] ;
		$langCode = $_GET['langCode'] ;
		$url = '' ;
		$path = '/static/uploads/uploaded_files/';
		if (isset($_FILES['upload']))
		{
		    $thumbnail = array(
				'w' => 1280,
				'h' => 1024
			);
			$file = ORM::factory('Esup_Common_File')
				->set_dir(DOCROOT.$path)
				->save_file($_FILES['upload'], 'unsigned', NULL, 0)
				->thumbnail($thumbnail);
			$url = $path.$thumbnail['w'].'x'.$thumbnail['h'].'_'.$file->file;
		}
		die('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'")</script>');
	}

}