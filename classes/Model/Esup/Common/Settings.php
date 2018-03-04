<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Settings extends Model_Esup {

	protected $_table_name = '_settings';

	public $options = array(
		'fields' => array(
			'set' => array(
				'type' => 'text',
				'label' => 'Название сета'
			),
			'title' => array(
				'type' => 'text',
				'label' => 'Заголовок'
			),
			'name' => array(
				'type' => 'text',
				'label' => 'Имя',
				'unique' => TRUE
			),
			'value' => array(
				'type' => 'text',
				'label' => 'Значение'
			)
		),
        'filters' => array(
            'search_query' => array(
                'type' => 'text',
                'fields' => array('title', 'name', 'value'), // Может быть массивом, например: array('title', 'text')
                'render' => array(
                    'title' => 'Поиск'
                )
            ),
			/*'set' => array(
				'type' => 'select',
				'model' => 'Esup_Common_Settings',
				'fields' => 'set',
				'render' => array(
					'model_title_field' => 'set',
					'model_value_field' => 'set',
					'title' => 'Набор'
				)
			),*/
			'set' => array(
				'type' => 'select2',
				'model' => 'Esup_Common_Settings',
				'fields' => 'set',
				'render' => array(
					'title' => 'Набор',
					'title_field' => 'set',
					'value_field' => 'set',
					'order_field' => 'title',
					'order_direction' => 'ASC'
				)
			)
        ),
		'render' => array(
			'title' => 'Настройки',
			'link' => 'settings'
		)
	);

	public function get_available_sets() {
		return DB::select('set')
			->from($this->_table_name)
			->distinct('set')
			->execute()
			->as_array();
	}

    public function get_config_db() {
    	$cache_instance = Cache::instance(CACHE_DRIVER);
        $result = $cache_instance->get(CP.'config_db_site');
        if ($result) {
            $data_source = $result;
        }
        if (empty($data_source)) {
            $data_source = $this->_get_config_db();
            $cache_instance->set(CP.'config_db_site', $data_source);
        }
        return $data_source;
    }

	private function _get_config_db() {
		$res = array();
		$model = $this->find_all();
		foreach ($model as $key => $item) {
			$res[$item->name] = $item->value;
		}
		return $res;
	}

}