<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Text extends Model_Esup {
	protected $_table_name = 'text';

	public $options = array(
		'fields' => array(
			'title' => array(
				'type' => 'text',
				'label' => 'Имя',
			),
			'key' => array(
				'type' => 'text',
				'label' => 'Ключ',
				'transliterate' => array(
					'from_field' => 'title'
				)
			),
			'value' => array(
				'type' => 'text',
				'label' => 'Значение',
				'translate' => TRUE
				/*'code' => TRUE*/
			),
		),
		'filters' => array(
			'search_query' => array(
				'type' => 'text',
				'fields' => array('title', 'value', 'key'), // Может быть массивом, например: array('title', 'text')
				'render' => array(
					'title' => 'Поиск'
				)
			)
		),
		'render' => array(
			'form' => TRUE,
			'title' => 'Текст на сайте',
			'link' => 'text',
		)
	);

	public function clear_text_cache()
	{
		$languages = ORM::factory('Esup_Common_Language')
			->find_all();
		$cache_instance = Cache::instance(CACHE_DRIVER);
		$cache_instance->delete(CP.'orm_sitetext_all');
		foreach ($languages as $key => $language)
		{
			$cache_instance->delete(CP.'orm_sitetext_all_'.$language->key);
		}
	}

    /* Возвращает статичный текст сайт из базы данных */
    public function get_text()
    {
    	$cache_instance = Cache::instance(CACHE_DRIVER);
        $result = $cache_instance->get(CP.'orm_sitetext_all'.self::$postfix);
        if ($result)
        {
            $data_source = $result;
        }
        if (empty($data_source))
        {
            $data_source = $this->_get_text();
            $cache_instance->set(CP.'orm_sitetext_all'.self::$postfix, $data_source);
        }
        return $data_source;
    }

	private function _get_text()
	{
		$model = $this->find_all();
		foreach ($model as $key => $item)
		{
			$res[$item->key] = (empty($item->{'value'.self::$postfix})) ? $item->value : $item->{'value'.self::$postfix};
		}
		return $res;
	}

}