<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Languages extends Controller_Esup_Common_Crud {

	public $access_level = 10;
	public $model_name = 'Esup_Common_Language';

	public function action_add() {
		$model = ORM::factory('Esup_Common_Language');
		if (isset($_POST['add'])) {
			$model->key = $this->request->post('key');
			$model->title = $this->request->post('title');
			$model->visible_name = $this->request->post('visible_name');
			$model->active = ($this->request->post('active')) ? 1 : 0;
			try {
				$model->save();
				$model->add_multilingual_fields($this->config_esup);
				$this->cache_instance->delete(CP.'orm_languages_active');
				$this->session->set('flash', array('status' => 'ok', 'message' => 'Запись добавлена.'));
			} catch (Exception $e) {
				if ($e->getCode() == 1062) {
					$this->session->set('flash', array(
						'status' => 'error', 
						'message' => 'Дублирование уникального идентификатора.'
					));
				} else {
					$this->session->set('flash', array(
						'status' => 'error', 
						'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
					));
				}
			}
			$this->redirect('esup/languages');
		} else {
			$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/add_form')
				->set('model', $model);
		}
	}

	public function action_edit() {
		$model = ORM::factory('Esup_Common_Language', $this->request->param('id'));
		if ($model->loaded() == FALSE) {
			$this->session->set('flash', array('status' => 'error', 'message' => 'Запись не найдена.'));
			$this->redirect('esup/languages');
		}
		if (isset($_POST['edit'])) {
			$model->title = $this->request->post('title');
			$model->visible_name = $this->request->post('visible_name');
			$model->active = ($this->request->post('active')) ? 1 : 0;
			try {
				$model->save();
				$this->cache_instance->delete(CP.'orm_languages_active');
				$this->session->set('flash', array('status' => 'ok', 'message' => 'Запись отредактирована.'));
			} catch (Exception $e) {
				if ($e->getCode() == 1062) {
					$this->session->set('flash', array(
						'status' => 'error', 
						'message' => 'Дублирование уникального идентификатора.'
					));
				} else {
					$this->session->set('flash', array(
						'status' => 'error', 
						'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
					));
				}
			}
			$this->redirect('esup/languages/edit/'.$model->id);
		}
		$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/edit_form')
			->set('model', $model);
	}

	public function action_delete() {
		$model = ORM::factory('Esup_Common_Language', $this->request->param('id'));
		if ($model->loaded()) {
			$model->delete_multilingual_fields($this->config_esup);
			$model->delete();
			$this->cache_instance->delete(CP.'orm_languages_active');
			$this->session->set('flash', array('status' => 'ok', 'message' => 'Запись удалена.'));
		} else {
			$this->session->set('flash', array('status' => 'error', 'message' => 'Запись не найдена.'));
		}
		$this->redirect('esup/languages');
	}

	public function action_active() {
		try {
			$id = $this->request->post('id');
			$id = explode('_', $id);
			$model = ORM::factory('Esup_Common_Language', $id['1']);
			$model->active = ($this->request->post('value') == 'true') ? 1 : 0;
			$model->save();
			$this->cache_instance->delete(CP.'orm_languages_active');
			die(json_encode(array('status' => 'ok')));
		} catch (Exception $e) {
			die(json_encode(array('status' => 'error', 'message' => $e->getMessage().'. Code: '.$e->getCode().'.')));
		}
	}

	public function action_refresh() {
		try {
			$result = ORM::factory('Esup_Common_Language')
				->add_multilingual_fields($this->config_esup);
			$this->session->set('flash', array('status' => 'ok', 'message' => 'Мультиязычные модели обновлены.'));
		} catch (Exception $e) {
			$this->session->set('flash', array('status' => 'error', 'message' => $e->getMessage().'. Code: '.$e->getCode().'.'));
		}
		$this->redirect('esup/languages');
	}

	public function action_set() {
		$this->session->set('lang', $this->request->param('id'));
		$this->redirect($this->request->referrer());
	}

}