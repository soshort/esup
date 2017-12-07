<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Administrator extends Model_Esup {

	protected $_table_name = 'administrators';

	public $options = array(
		'fields' => array(
			'login' => array(
				'type' => 'text',
				'label' => 'Логин'
			),
			'password' => array(
				'type' => 'password',
				'label' => 'Пароль'
			),
			'fio' => array(
				'type' => 'text',
				'label' => 'ФИО'
			),
			'access_level' => array(
				'type' => 'text',
				'label' => 'Уровень доступа'
			)
		),
        /* Фильтры */
        'filters' => array(
            'search_query' => array(
                'type' => 'text',
                'fields' => array('login', 'fio'), // Может быть массивом, например: array('title', 'text')
                'render' => array(
                    'title' => 'Поиск'
                )
            )
        ),
		'render' => array(
			'form' => TRUE,
			'title' => 'Администраторы',
			'link' => 'administrators'
		)
	);

}