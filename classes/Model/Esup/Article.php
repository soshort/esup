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
		),
	);

	public $options = array(
		'fields' => array(
            'active' => array(
                'label' => 'Активен',
                'type' => 'checkbox',
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
				'type' => 'belongs_to',
				'label' => 'Категория',
				'relation' => array(
					'model' => 'Esup_Article_Category',
					'id_field' => 'id',
					'title_field' => 'title'
				),
				'default' => 'category_id',
				'show_default_value' => TRUE
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
					'400x300' => array(
						'w' => 400,
						'h' => 300,
						'crop' => array('x' => NULL, 'y' => 0)
					),
					'1280x' => array(
						'w' => 1280,
						'h' => NULL,
					)
				),
				'remove_original' => TRUE,
				'esup_thumbnail' => '400x300',
				'esup_fullsize' => '1280x',
				'multiple' => TRUE
			),
		),
		'filters' => array(
			'category_id' => array(
				'type' => 'select',
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