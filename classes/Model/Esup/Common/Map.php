<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Map extends Model_Esup {

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

}