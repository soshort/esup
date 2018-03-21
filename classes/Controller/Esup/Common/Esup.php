<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Esup extends Controller_Esup_Common {

	public function action_index()
	{
		$this->template->content = View::factory('esup_pages/index');
	}

}