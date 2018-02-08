<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Social extends Model_Esup {

	protected $_table_name = 'social';
	protected $_has_many = array(
		'files' => array(
			'model'  => 'Esup_Common_File',
			'foreign_key' => 'item_id'
		),
	);

	public $options = array(
		'fields' => array(
            'active' => array(
                'label' => 'Активен',
                'type' => 'checkbox'
            ),
			'title' => array(
				'label' => 'Заголовок',
				'type' => 'text',
				'translate' => TRUE
			),
			'link' => array(
				'label' => 'Ссылка',
				'type' => 'text',
				'translate' => TRUE
			),
		),
		'files' => array(
			'social' => array(
				'label' => 'Изображение',
			),
		),
		'filters' => array(
			'search_query' => array(
				'type' => 'text',
				'fields' => 'title', // Может быть массивом, например: array('title', 'text')
				'render' => array(
					'title' => 'Поиск'
				)
			)
		),
		'render' => array(
			'list' => array(
				'marker_header' => 'Заголовок',
				'marker_field' => 'title',
				'sort' => array(
					'field' => 'sort',
					'order' => 'ASC'
				)
			),
			'form' => TRUE,
			'title' => 'Социальные сети',
			'link' => 'social'
		)
	);

}