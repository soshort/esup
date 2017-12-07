<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Language extends Model_Esup {

	protected $_table_name = 'languages';
	protected $_lang = '';
	protected $_postfix = '';

	public $options = array(
		'fields' => array(
			'key' => array(
				'type' => 'text',
				'label' => 'Ключ'
			),
			'title' => array(
				'type' => 'text',
				'label' => 'Заголовок'
			),
			'visible_name' => array(
				'type' => 'text',
				'label' => 'Видимый на сайте заголовок'
			),
		),
        'render' => array(
            'title' => 'Языки',
            'link' => 'languages'
        )
	);

	public function get_instance($lang_key, $lang_key_default = 'ru') {
		$language = $this->where('key', '=', $lang_key)
			->find();
		if ($language->loaded()) {
			$this->_lang = $language->key;
			$this->_postfix = '_'.$language->key;
		} else {
			$this->_lang = $lang_key_default;
			$this->_postfix = '';
		}
		return $this;
	}

	public function get_lang() {
		return $this->_lang;
	}

	public function get_postfix() {
		return $this->_postfix;
	}

    /* Возвращает список активных языков сайта из базы данных */
    public function get_active($lang_default) {
        $result = self::$cache_instance->get(CP.'orm_languages_active');
        if ($result) {
            $data_source = $result;
        }
        if (empty($data_source)) {
            $data_source = $this->get_active_as_array($lang_default);
            self::$cache_instance->set(CP.'orm_languages_active', $data_source);
        }
        return $data_source;
    }

	private function get_active_as_array($lang_default) {
		$res = array();
		$languages = $this->where('active', '=', 1)
			->find_all();
		foreach ($languages as $key => $item) {
			$res[$item->key] = array(
				'title' => $item->title,
				'visible_name' => $item->visible_name
			);
		}
		$res[$lang_default['key']] = array(
			'title' => $lang_default['title'],
			'visible_name' => $lang_default['visible_name'],
		);
		return $res;
	}

	/* Добавление мультиязычных полей во все указанные в конфиге моделей */
	public function add_multilingual_fields($config_esup) {
		$languages = ORM::factory('Esup_Common_Language')->find_all();
		foreach ($config_esup->get('multilingual_models') as $key => $m) {
			$model = ORM::factory($m);
			foreach ($model->options['fields'] as $field_name => $options) {
				$field_datatype = DB::query(Database::SELECT, 'SHOW FIELDS FROM  `'.$model->table_name().'` WHERE Field = "'.$field_name.'"')
					->as_object()
					->execute()
					->current();
				if (isset($options['translate']) && $options['translate'] == TRUE) {
					foreach ($languages as $key => $language) {
						try {
							$table_name = $model->table_name();
							$new_field_name = $field_name.'_'.$language->key;
							$new_field_type = strtoupper($field_datatype->Type);
							$new_field_null = ($field_datatype->Null == 'YES') ? 'NULL' : 'NOT NULL';
							DB::query(NULL, 'ALTER TABLE `'.$table_name.'` ADD `'.$new_field_name.'` '.$new_field_type.' '.$new_field_null.' AFTER `'.$field_name.'`')
								->execute();
						} catch (Exception $e) {
							if ($e->getCode() == 1060) {
								continue;
							} else {
								throw $e;
							}
						}
					}
				}
			}
		    self::$cache_instance->delete(CP.$model->table_name().'_structure');
		}
	}

	/* Удаление мультиязычных полей из всех указанных в конфиге моделей */
	public function delete_multilingual_fields($config_esup) {
		$language = $this->key;
		foreach ($config_esup->get('multilingual_models') as $key => $m) {
			$model = ORM::factory($m);
			foreach ($model->options['fields'] as $field => $options) {
				if (isset($options['translate']) && $options['translate'] == TRUE) {
					try {
						DB::query(NULL, 'ALTER TABLE `'.$model->table_name().'` DROP `'.$field.'_'.$language.'`')
							->execute();
					} catch (Exception $e) {
						continue;
					}
				}
			}
		    self::$cache_instance->delete(CP.$model->table_name().'_structure');
		}
	}

}