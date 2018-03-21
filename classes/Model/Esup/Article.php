<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Article extends Model_Esup {

	protected $_table_name = 'articles';
	protected $_belongs_to = array(
		'category' => array(
			'model' => 'Esup_Article_Category',
			'foreign_key' => 'category_id',
		)
	);
	protected $_has_many = array(
		'files' => array(
			'model'  => 'Esup_Common_File',
			'foreign_key' => 'item_id'
		)
	);

	public $options = array(
		'fields' => array(
            'active' => array(
                'label' => 'Активен',
                'type' => 'checkbox',
                'default' => TRUE
            ),
			'title' => array(
				'label' => 'Заголовок',
				'type' => 'text',
				'translate' => TRUE
			),
			'link' => array(
				'type' => 'text',
				'label' => 'Ссылка',
				'transliterate' => array(
					'from_field' => 'title'
					/*'unique' => TRUE*/
				)
			),
			'category_id' => array(
				'type' => 'select2',
				'model' => 'Esup_Article_Category',
				'render' => array(
					'title' => 'Категория',
					'title_field' => 'title',
					'value_field' => 'id',
					'order_field' => 'sort',
					'order_direction' => 'ASC'
				)
			),
			'text_short' => array(
				'label' => 'Коротко',
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
			),
			'creation_time' => array(
				'label' => 'Дата добавления',
				'type' => 'date'
			)
		),
		'files' => array(
			'article_image' => array(
				'label' => 'Изображение статьи',
				'thumbnails' => array(
					'640x480' => array(
						'w' => 640,
						'h' => 480,
                        'background' => array(
                            'color' => '#F1F1F1'
                        )
					),
					'1280x' => array(
						'w' => 1280,
						'h' => NULL
					)
				),
				'multiple' => TRUE,
				'esup_thumbnail' => '640x480'
			),
		),
		'filters' => array(
			'category_id' => array(
				'type' => 'select2',
				'model' => 'Esup_Article_Category',
				'fields' => 'category_id',
				'render' => array(
					'model_title_field' => 'title',
					'model_value_field' => 'id',
					'title' => 'Категория'
				)
			),
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
			'title' => 'Статьи',
			'link' => 'articles'
		)
	);

}