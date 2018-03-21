<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Gallery extends Model_Esup {

	protected $_table_name = 'galleries';
	protected $_has_many = array(
		'files'  => array(
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
				),
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
		'files' => array(
			'gallery_image' => array(
				'label' => 'Изображения галереи',
				'multiple' => TRUE,
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
			'title' => 'Галереи',
			'link' => 'galleries'
		)
	);

}