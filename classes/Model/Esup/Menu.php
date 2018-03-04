<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Menu extends Model_Esup {

	protected $_table_name = 'menu';
	protected $_belongs_to = array(
		'parent' => array(
			'model' => 'Esup_Menu',
			'foreign_key' => 'parent_id',
		),
		'page' => array(
			'model' => 'Esup_Page',
			'foreign_key' => 'page_id',
		)
	);
	protected $_has_many = array(
		'menu' => array(
			'model'  => 'Esup_Menu',
			'foreign_key' => 'parent_id'
		)
	);

    public $options = array(
        /* Поля БД */
        'fields' => array(
            'active' => array(
                'label' => 'Активен',
                'type' => 'checkbox'
            ),
			'title' => array(
				'type' => 'text',
				'label' => 'Заголовок',
				'translate' => TRUE,
			),
			'parent_id' => array(
				'type' => 'select2',
				'model' => 'Esup_Menu',
				'render' => array(
					'title' => 'Родительский пункт',
					'title_field' => 'title',
					'value_field' => 'id',
					'order_field' => 'sort',
					'order_direction' => 'ASC'
				),
				'nested' => array(
					'field' => 'parent_id'
				)
			),
			'page_id' => array(
				'type' => 'select2',
				'model' => 'Esup_Page',
				'render' => array(
					'title' => 'Привязать к странице',
					'title_field' => 'title',
					'value_field' => 'id',
					'order_field' => 'sort',
					'order_direction' => 'ASC'
				)
			),
			'link' => array(
				'type' => 'text',
				'label' => 'Ссылка',
				'transliterate' => array(
					'from_field' => 'title'
				)
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
			),
			'parent_id' => array(
				'type' => 'select2',
				'model' => 'Esup_Menu',
				'fields' => 'parent_id',
				'render' => array(
					'title' => 'Родительский пункт',
					'title_field' => 'title',
					'value_field' => 'id',
					'order_field' => 'sort',
					'order_direction' => 'ASC'
				),
				'nested' => array(
					'field' => 'parent_id'
				)
			)
		),
        /* Опции вывода списка записей и формы */
        'render' => array(
			'tree_structure' => array(
				'relation' => 'menu',
				'field' => 'parent_id'
			),
            'list' => array(
                'marker_header' => 'Заголовок',
                'marker_field' => 'title',
                'sort' => array(
                    'field' => 'sort',
                    'order' => 'ASC'
                )
            ),
            'form' => TRUE,
            'title' => 'Меню сайта',
            'link' => 'menu',
            'hint' => 'Если к пункту меню будет привязана статичная страница, то поле «ссылка» уже не имеет значения и его можно оставить пустым.'
        )
    );

}