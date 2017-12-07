<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Fmanager extends Controller_Esup_Common {

	public function action_index() {
		$this->template->content = View::factory('esup_pages/fmanager/index');
	}

	public function action_init() {
		require DOCROOT.'vendor/studio-42/elfinder/php/autoload.php';
		error_reporting(0);
		function access($attr, $path, $data, $volume) {
			return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
				? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
				:  NULL;                                    // else elFinder decide it itself
		}
		$opts = array(
			// 'debug' => true,
			'roots' => array(
				array(
					'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
					'path'          => DOCROOT.'static/uploads/uploaded_files/',         // path to files (REQUIRED)
					'URL'           => '/static/uploads/uploaded_files/', // URL to files (REQUIRED)
					'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
				)
			)
		);
		// run elFinder
		$connector = new elFinderConnector(new elFinder($opts));
		$connector->run();
	}

}