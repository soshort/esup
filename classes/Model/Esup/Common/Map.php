<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Map extends Model_Esup {

	private $_map = array();

	protected $_table_name = 'maps';

	public $options = array(
		'fields' => array(
			'title' => array(
				'type' => 'text',
				'label' => 'Заголовок',
				'translate' => TRUE
			),
			'name' => array(
				'type' => 'text',
				'label' => 'Имя',
				'transliterate' => array(
					'from_field' => 'title'
				),
				'unique' => TRUE
			),
			'coordinates' => array(
				'type' => 'map',
				'label' => 'Координаты'
			)
		),
		'filters' => array(
			'search_query' => array(
				'type' => 'text',
				'fields' => array('title', 'name'), // Может быть массивом, например: array('title', 'text')
				'render' => array(
					'title' => 'Поиск'
				)
			)
		),
		'render' => array(
			'list' => array(
				'marker_header' => 'Заголовок',
				'marker_field' => 'title',
			),
			'form' => TRUE,
			'title' => 'Метки на карте',
			'link' => 'maps',
		)
	);

	public function get_data($data_string, $value)
	{
		if (empty($this->_map))
		{
			$this->set_map($data_string);
		}
		return $this->_map[$value];
	}

	private function set_map($data_string)
	{
		$data_string = (empty($data_string)) ? '43.295904,76.943776:10' : $data_string;
		$data = explode(':', $data_string);
		$coordinates = explode(',', $data[0]);
		$this->_map = array(
			'lat' => $coordinates[0],
			'lng' => $coordinates[1],
			'zoom' => $data[1]
		);
	}

}