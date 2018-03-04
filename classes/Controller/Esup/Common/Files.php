<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Files extends Controller_Esup_Common_Crud {

	public $model_name = 'Esup_Common_File';

	public function action_add() {
		throw new HTTP_Exception_404();
	}

	public function action_delete() {
		try {
			$model = ORM::factory($this->model_name, $this->request->param('id'));
			if ($model->loaded()) {
				if ($model->item_id == NULL) {
					$model->set_path(DOCROOT.'/static/uploads/uploaded_files/')
						->delete_file();
				} else {
					$model->delete_file();
				}
				$result_array = array(
					'status' => 'ok',
					'message' => 'Файл удален.'
				);
			} else {
				$result_array = array(
					'status' => 'error',
					'message' => 'Файл не найден.'
				);
			}
		} catch (Exception $e) {
			$result_array = array(
				'status' => 'error',
				'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
			);
		}
		if ($this->request->is_ajax()) {
			die(json_encode($result_array));
		} else {
			$this->session->set('flash', $result_array);
			$this->redirect('esup/files');
		}
	}

	public function action_set_main() {
		try {
			DB::update(ORM::factory($this->model_name)->table_name())
				->set(array('main' => 0))
				->where('table_name', '=', Arr::get($_POST, 'table'))
				->and_where('item_id', '=', Arr::get($_POST, 'item_id'))
				->execute();
			$model = ORM::factory($this->model_name, Arr::get($_POST, 'id'));
			$model->main = 1;
			$model->save();
			die(json_encode(array(
				'status' => 'ok',
				'message' => 'Основное изображение изменено.'
			)));
		} catch (Exception $e) {
			die(json_encode(array(
				'status' => 'error',
				'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
			)));
		}
	}

	public function action_rotate() {
		try {
			$file = ORM::factory($this->model_name, $this->request->param('id'));
			$file->rotate_file(Arr::get($_POST, 'degrees'));
			die(json_encode(array(
				'status' => 'ok',
				'message' => 'Изображение было повернуто.'
			)));
		} catch (Exception $e) {
			die(json_encode(array(
				'status' => 'error',
				'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
			)));
		}
	}

}