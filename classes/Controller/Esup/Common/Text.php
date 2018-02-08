<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Text extends Controller_Esup_Common_Crud {

	public $model_name = 'Esup_Common_Text';

	public function action_index() {
		$limit = $this->config_db['items_per_page'];
		$model = ORM::factory($this->model_name);
		$list = $model;
		$list = $list->apply_filters($model->options['filters']);
		$total_items_model = clone $list;
		$list = $list->order_by('sort', 'ASC')
			->order_by('id', 'DESC')
			->limit($limit)
			->set_offset(Arr::get($_GET, 'p'), $limit)
			->find_all();
		$total_items = $total_items_model->count_all();
		$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/index')
			->set('list', $list)
			->set('total_items', $total_items)
			->set('items_per_page', $limit)
			->set('model', $model);
	}

	public function action_add() {
		$model = ORM::factory($this->model_name);
		if (Arr::get($_POST, 'add')) {
			$model->fill($model->options['fields']);
			try {
				$model->save();
				$model->clear_text_cache();
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'Запись добавлена.'
				));
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
			$this->redirect('esup/'.$model->options['render']['link'].$this->url_query);
		} else {
			if (isset($model->options['render']['form'])) {
				$this->template->content = View::factory('esup_pieces/default_form');
			} else {
				$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/add');
			}
			$this->template->content->set('model', $model);
		}
	}

	public function action_edit() {
		$model = ORM::factory($this->model_name, $this->request->param('id'));
		if ($model->loaded() == FALSE) {
			$this->session->set('flash', array(
				'status' => 'error',
				'message' => 'Запись не найдена.'
			));
			$this->redirect('esup/'.$model->options['render']['link'].$this->url_query);
		}
		if (Arr::get($_POST, 'edit')) {
			$model->fill($model->options['fields']);
			try {
				$model->save();
				$model->clear_text_cache();
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'Запись отредактирована.'
				));
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
			$this->redirect('esup/'.$model->options['render']['link'].'/edit/'.$model->id.$this->url_query);
		}
		if (Arr::get($model->options['render'], 'form')) {
			$this->template->content = View::factory('esup_pieces/default_form');
		} else {
			$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/edit');
		}
		$this->template->content->set('model', $model);
	}

	public function action_delete() {
		$model = ORM::factory($this->model_name, $this->request->param('id'));
		if ($model->loaded()) {
			$model->clear_text_cache();
			$model->delete();
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Запись удалена.'
			));
		} else {
			$this->session->set('flash', array(
				'status' => 'error',
				'message' => 'Запись не найдена.'
			));
		}
		$this->redirect('esup/'.$model->options['render']['link'].$this->url_query);
	}

	public function action_multiple() {
		$count = 0;
		$items = array_filter(explode(',', Arr::get($_POST, 'items', '')));
		$action = Arr::get($_POST, 'action', '');
		if ($action == 'delete') {
			foreach ($items as $key => $id) {
				$model = ORM::factory($this->model_name, $id);
				if ($model->loaded()) {
					$model->delete();
					$count = $count + 1;
				}
			}
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Удалено '.$count.' из '.count($items).' элементов.'
			));
		} else {
			$this->session->set('flash', array(
				'status' => 'error',
				'message' => 'Выберите действие.'
			));
		}
		$model = ORM::factory($this->model_name);
		$model->clear_text_cache();
		$this->redirect('esup/'.$model->options['render']['link'].$this->url_query);
	}

}