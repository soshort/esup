<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Administrators extends Controller_Esup_Common_Crud {

	public $model_name = 'Esup_Common_Administrator';

	public function action_index()
	{
		$limit = $this->config_db['items_per_page'];
		$model = ORM::factory($this->model_name);
		$list = $model;
		$list = $model->where('access_level', '<=', $this->admin->access_level);
		$list = $list->apply_filters($model->options['filters']);
		/* Модель для подсчета все записей */
		$total_items_model = clone $list;
		$list = $list->order_by('id', 'DESC')
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

	public function action_add()
	{
		$model = ORM::factory($this->model_name);
		if (isset($_POST['add']))
		{
			$model->login = Arr::get($_POST, 'login');
			$model->password = md5(Arr::get($_POST, 'password'));
			$model->fio = Arr::get($_POST, 'fio');
			$model->access_level = ($this->admin->access_level <= Arr::get($_POST, 'access_level'))
								 ? $this->admin->access_level
								 : Arr::get($_POST, 'access_level');
			$model->save();
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Запись добавлена.
			'));
			$this->redirect('esup/'.$model->options['render']['link']);
		}
		else
		{
			if (isset($model->options['render']['form']))
			{
				$this->template->content = View::factory('esup_pieces/default_form');
			}
			else
			{
				$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/add');
			}
			$this->template->content->set('model', $model);
		}
	}

	public function action_edit() {
		$model = ORM::factory($this->model_name)
			->where('id', '=', $this->request->param('id'))
			->and_where('access_level', '<=', $this->admin->access_level)
			->find();
		if ($model->loaded() == FALSE)
		{
			$this->session->set('flash', array(
				'status' => 'error',
				'message' => 'Запись не найдена.'
			));
			$this->redirect('esup/'.$model->options['render']['link']);
		}
		if (isset($_POST['edit']))
		{
			$model->password = (Arr::get($_POST, 'password'))
							 ? md5(Arr::get($_POST, 'password'))
							 : $model->password;
			$model->access_level = ($this->admin->access_level <= Arr::get($_POST, 'access_level'))
								 ? $this->admin->access_level
								 : Arr::get($_POST, 'access_level');
			$model->login = Arr::get($_POST, 'login');
			$model->fio = Arr::get($_POST, 'fio');
			$model->save();
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Запись отредактирована.
			'));
			$this->redirect('esup/'.$model->options['render']['link'].'/edit/'.$model->id);
		}
		if (isset($model->options['render']['form']))
		{
			$this->template->content = View::factory('esup_pieces/default_form');
		}
		else
		{
			$this->template->content = View::factory('esup_pages/'.$model->options['render']['link'].'/edit');
		}
		$this->template->content->set('model', $model);
	}

	public function action_delete()
	{
		$model = ORM::factory('Esup_Common_Administrator')
			->where('id', '=', $this->request->param('id'))
			->and_where('access_level', '<=', $this->admin->access_level)
			->find();
		if ($model->loaded())
		{
			$model->delete();
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Запись удалена.'
			));
		}
		else
		{
			$this->session->set('flash', array(
				'status' => 'error',
				'message' => 'Запись не найдена.'
			));
		}
		$this->redirect('esup/'.$model->options['render']['link']);
	}

	public function action_multiple()
	{
		$count = 0;
		$items = array_filter(explode(',', Arr::get($_POST, 'items', '')));
		$action = Arr::get($_POST, 'action', '');
		if ($action == 'delete')
		{
			foreach ($items as $key => $id)
			{
				$model = ORM::factory($this->model_name)
					->where('id', '=', $id)
					->and_where('access_level', '<=', $this->admin->access_level)
					->find();
				if ($model->loaded())
				{
					$model->delete_files();
					$model->delete();
					$count = $count + 1;
				}
			}
			$this->session->set('flash', array(
				'status' => 'ok',
				'message' => 'Удалено '.$count.' из '.count($items).' элементов.'
			));
		}
		else
		{
			$this->session->set('flash', array(
				'status' => 'error',
				'message' => 'Выберите действие.'
			));
		}
		$model = ORM::factory($this->model_name);
		$this->redirect('esup/'.$model->options['render']['link'].$this->url_query);
	}

}