<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Auth extends Controller_Template {

	public $template = 'esup_layout/auth';

	public function action_index() {
		$error = FALSE;
		if (Arr::get($_POST, 'auth')) {
			$admin = ORM::factory('Esup_Common_Administrator', array(
	            'login' => Arr::get($_POST, 'login'),
	            'password' => md5(Arr::get($_POST, 'password')),
	        ));
	        if ($admin->loaded() == FALSE || Captcha::valid(Arr::get($_POST, 'captcha_code')) == FALSE || Security::check(Arr::get($_POST, 'csrf')) == FALSE) {
	        	$error = TRUE;
	        } else {
	        	Cookie::set('admin', $admin->id);
		        $this->redirect('esup');
	        }
		}
		$this->template->title = 'ESUP - Админ панель';
		$this->template->content = View::factory('esup_pages/auth/index')
			->set('error', $error);
	}

	public function action_logout() {
		Cookie::delete('admin');
		$this->redirect('esup/auth');
	}

}