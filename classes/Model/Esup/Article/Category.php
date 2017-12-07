<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Article_Category extends Model_Esup {

	protected $_table_name = 'article_categories';
	protected $_has_many = array(
		'files'  => array(
			'model'  => 'Esup_Common_File',
			'foreign_key' => 'item_id'
		),
		'articles'  => array(
			'model'  => 'Esup_Article',
			'foreign_key' => 'category_id'
		),
	);

    public $options = array(
        /* Поля БД */
        'fields' => array(
            'visible' => array(
                'label' => 'Показывать',
                'type' => 'checkbox',
            ),
            'title' => array(
                'label' => 'Заголовок',
                'type' => 'text'
            ),
            'link' => array(
                'type' => 'text',
                'label' => 'Ссылка',
                'transliterate' => array(
                    'from_field' => 'title'
                ),
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
        ),
        /* Файлы */
        'files' => array(
            'article_category' => array(
                'label' => 'Изображение для категории статей',
                'thumbnails' => array(
                    '166x166' => array(
                        'w' => 166,
                        'h' => 166,
                        'crop' => array('x' => NULL, 'y' => 0)
                    ),
                ),
                'esup_thumbnail' => '166x166',
            )
        ),
        /* Фильтры */
        'filters' => array(
            'search_query' => array(
                'type' => 'text',
                'fields' => array('title', 'link'), // Может быть массивом, например: array('title', 'text')
                'render' => array(
                    'title' => 'Поиск'
                )
            )
        ),
        /* Опции вывода списка записей и формы */
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
            'title' => 'Категории статей',
            'link' => 'article_categories'
        )
    );

}