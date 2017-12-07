<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Slider extends Model_Esup {

	protected $_table_name = 'slider';
	protected $_has_many = array(
		'files' => array(
			'model'  => 'Esup_Common_File',
			'foreign_key' => 'item_id'
		),
	);

	public $options = array(
		'fields' => array(
			'title' => array(
				'label' => 'Заголовок',
				'type' => 'text',
				'translate' => TRUE
			),
			'text' => array(
				'label' => 'Текст',
				'type' => 'textarea',
				'translate' => TRUE
			),
			'page_id' => array(
				'type' => 'belongs_to',
				'label' => 'Привязать к странице',
				'relation' => array(
					'model' => 'Esup_Page',
					'id_field' => 'id',
					'title_field' => 'title'
				),
				'show_default_value' => TRUE
			),
			'link' => array(
				'type' => 'text',
				'label' => 'Ссылка',
				'transliterate' => array(
					'from_field' => 'title'
				)
			)
		),
		'files' => array(
			'slider_image' => array(
				'label' => 'Изображение',
				/*'thumbnails' => array(
					'1366x600' => array(
						'w' => 1366,
						'h' => 600,
						'crop' => array('x' => NULL, 'y' => NULL)
					),
				),*/
				/*'esup_fullsize' => '1366x600',
				'esup_thumbnail' => '1366x600',
				'remove_original' => TRUE*/
				'multiple' => TRUE
			)
		),
		'filters' => array(
			'search_query' => array(
				'type' => 'text',
				'fields' => array('title', 'link'), // Может быть массивом, например: array('title', 'text')
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
			'title' => 'Слайдер',
			'link' => 'slider'
		)
	);

}