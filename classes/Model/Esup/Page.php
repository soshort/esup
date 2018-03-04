<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Page extends Model_Esup {

	protected $_table_name = 'pages';
	protected $_has_one = array(
		'menu' => array(
			'model'  => 'Esup_Menu',
			'foreign_key' => 'page_id'
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
				'transliterate' => array(
					'from_field' => 'title'
				),
			),
			'text_short' => array(
				'label' => 'Текст коротко',
				'type' => 'ckeditor',
				'translate' => TRUE
			),
			'text' => array(
				'label' => 'Текст',
				'type' => 'ckeditor',
				'translate' => TRUE
			),
			'meta_title' => array(
				'label' => 'Meta title',
				'type' => 'text',
				'translate' => TRUE
			),
			'meta_keywords' => array(
				'label' => 'Meta keywords',
				'type' => 'text',
				'translate' => TRUE
			),
			'meta_description' => array(
				'label' => 'Meta description',
				'type' => 'text',
				'translate' => TRUE
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
			/*'list' => array(
				'marker_header' => 'Заголовок',
				'marker_field' => 'title',
				'sort' => array(
					'field' => 'sort',
					'order' => 'ASC'
				)
			),*/
			'form' => TRUE,
			'title' => 'Страницы',
			'link' => 'pages'
		)
	);

}