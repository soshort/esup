<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Mailer extends Controller_Esup_Common_Crud {

	public $model_name = 'Esup_Common_Mailer';

	public function action_view() {
		$model = ORM::factory('Esup_Common_Mailer', $this->request->param('id'));
		if ($model->loaded() == FALSE) {
			$this->session->set('flash', array('status' => 'error', 'message' => 'Запись не найдена.'));
			$this->redirect('esup/'.$model->options['render']['link'].$this->url_query);
		}
		$this->template->content = View::factory('esup_pages/mailer/view')
			->set('model', $model);
	}

	public function action_send() {
		try {
			$result = ORM::factory('Esup_Common_Mailer', $this->request->param('id'))
				->send();
			$this->session->set('flash', array(
				'status' => 'ok', 
				'message' => 'Отправлено писем: '.$result['sended'].' из '.$result['total'].'.'
			));
		} catch (Exception $e) {
			$this->session->set('flash', array(
				'status' => 'error', 
				'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
			));
		}
		$this->redirect('esup/mailer'.$this->url_query);
	}

	public function action_send_all() {
		try {
			$result = ORM::factory('Esup_Common_Mailer')
				->send();
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Отправлено писем: '.$result['sended'].' из '.$result['total'].'.'
			));
		} catch (Exception $e) {
			$this->session->set('flash', array(
				'status' => 'error', 
				'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
			));
		}
		$this->redirect('esup/mailer'.$this->url_query);
	}

}