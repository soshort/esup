<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Sorting extends Controller_Esup_Common {

	public function action_index()
	{
		try
		{
			$field = Arr::get($_POST, 'field', 'id');
			DB::update(Arr::get($_POST, 'table'))
				->set(array($field => Arr::get($_POST, 'value')))
				->where('id', '=', Arr::get($_POST, 'id'))
				->execute();
			die(json_encode(array(
				'status' => 'ok',
				'message' => 'Записи отсортированы.'
			)));
		}
		catch (Exception $e)
		{
			die(json_encode(array(
				'status' => 'error',
				'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
			)));
		}
	}

}