<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Language extends Model_Esup {

	private $_default = array(
        'key' => 'ru',
        'title' => 'Русский',
        'visible_name' => 'Рус'
    );
	private $_lang = '';
	private $_postfix = '';

	protected $_table_name = 'languages';

	public $options = array(
		'fields' => array(
            'active' => array(
                'label' => 'Активен',
                'type' => 'checkbox',
            ),
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

	public function get_instance($key)
	{
		$language = $this->where('key', '=', $key)
			->find();
		if ($language->loaded())
		{
			$this->_lang = $language->key;
			$this->_postfix = '_'.$language->key;
		}
		else
		{
			$this->_lang = $this->_default['key'];
			$this->_postfix = '';
		}
		return $this;
	}

	public function get_lang()
	{
		return $this->_lang;
	}

	public function get_postfix()
	{
		return $this->_postfix;
	}

    /* Возвращает список активных языков сайта из базы данных */
    public function get_active()
    {
    	$cache_instance = Cache::instance(CACHE_DRIVER);
        $result = $cache_instance->get(CP.'orm_languages_active');
        if ($result)
        {
            $data_source = $result;
        }
        if (empty($data_source))
        {
            $data_source = $this->get_active_as_array();
            $cache_instance->set(CP.'orm_languages_active', $data_source);
        }
        return $data_source;
    }

	private function get_active_as_array()
	{
		$res = array();
		$languages = $this->where('active', '=', 1)
			->find_all();
		foreach ($languages as $key => $item)
		{
			$res[$item->key] = array(
				'title' => $item->title,
				'visible_name' => $item->visible_name
			);
		}
		$res[$this->_default['key']] = array(
			'title' => $this->_default['title'],
			'visible_name' => $this->_default['visible_name'],
		);
		return $res;
	}

	/* Добавление мультиязычных полей во все указанные в конфиге моделей */
	public function add_multilingual_fields($config_esup)
	{
		$languages = ORM::factory('Esup_Common_Language')
			->find_all();
		foreach ($config_esup->get('multilingual_models') as $key => $m)
		{
			$model = ORM::factory($m);
			foreach ($model->options['fields'] as $field_name => $options)
			{
				$field_datatype = DB::query(Database::SELECT, 'SHOW FIELDS FROM  `'.$model->table_name().'` WHERE Field = "'.$field_name.'"')
					->as_object()
					->execute()
					->current();
				if (isset($options['translate']) AND $options['translate'] == TRUE)
				{
					foreach ($languages as $key => $language)
					{
						try
						{
							$table_name = $model->table_name();
							$new_field_name = $field_name.'_'.$language->key;
							$new_field_type = strtoupper($field_datatype->Type);
							$new_field_null = ($field_datatype->Null == 'YES') ? 'NULL' : 'NOT NULL';
							DB::query(NULL, 'ALTER TABLE `'.$table_name.'` ADD `'.$new_field_name.'` '.$new_field_type.' '.$new_field_null.' AFTER `'.$field_name.'`')
								->execute();
						}
						catch (Exception $e)
						{
							if ($e->getCode() == 1060)
								continue;
							else
								throw $e;
						}
					}
				}
			}
			$cache_instance = Cache::instance(CACHE_DRIVER);
		    $cache_instance->delete(CP.$model->table_name().'_structure');
		}
	}

	/* Удаление мультиязычных полей из всех указанных в конфиге моделей */
	public function delete_multilingual_fields($config_esup)
	{
		$language = $this->key;
		foreach ($config_esup->get('multilingual_models') as $key => $m)
		{
			$model = ORM::factory($m);
			foreach ($model->options['fields'] as $field => $options)
			{
				if (isset($options['translate']) AND $options['translate'] == TRUE)
				{
					try
					{
						DB::query(NULL, 'ALTER TABLE `'.$model->table_name().'` DROP `'.$field.'_'.$language.'`')
							->execute();
					}
					catch (Exception $e)
					{
						continue;
					}
				}
			}
			$cache_instance = Cache::instance(CACHE_DRIVER);
		    $cache_instance->delete(CP.$model->table_name().'_structure');
		}
	}

}