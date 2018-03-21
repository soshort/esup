<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Esup_Common_Settings extends Controller_Esup_Common_Crud {

	public $model_name = 'Esup_Common_Settings';

	protected $access_level = 10;

	private $cache_instance;
	private $css_full_name = 'static/css/user.css';
	private $js_full_name = 'static/js/user.js';
	private $logo_path = 'static/images';
	private $favicon_name = 'favicon.ico';

	public function before()
	{
		parent::before();
		$this->cache_instance = Cache::instance(CACHE_DRIVER);
	}

	public function action_add()
	{
		if ($this->request->post('add'))
		{
			$this->cache_instance->delete(CP.'config_db_site');
		}
		parent::action_add();
	}

	public function action_edit()
	{
		if ($this->request->post('edit'))
		{
			$this->cache_instance->delete(CP.'config_db_site');
		}
		parent::action_edit();
	}

	public function action_delete()
	{
		$this->cache_instance->delete(CP.'config_db_site');
		parent::action_delete();
	}

	public function action_multiple()
	{
		$this->cache_instance->delete(CP.'config_db_site');
		parent::action_multiple();
	}

	public function action_scripts()
	{
		$this->assets->single->add('ace-editor-cdn');
		$this->assets->pipeline->add('settings-scripts-js');
		$model = ORM::factory($this->model_name);
		if (isset($_POST['save']))
		{
			try
			{
				file_put_contents(DOCROOT.$this->css_full_name, $this->request->post('css'));
				file_put_contents(DOCROOT.$this->js_full_name, $this->request->post('js'));
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'Изменения сохранены.'
				));
			}
			catch (Exception $e)
			{
				$this->session->set('flash', array(
					'status' => 'error',
					'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
				));
			}
			$this->redirect('esup/settings/scripts');
		}
		$this->template->content = View::factory('esup_pages/settings/scripts')
			->set('model', $model)
			->set('css', $this->get_css())
			->set('js', $this->get_js());
	}

	public function action_logo()
	{
		$model = ORM::factory($this->model_name);
		if ($this->request->post('save'))
		{
			try
			{
				if ($_FILES['logo']['error'] == 0)
				{
					$logo_name = ORM::factory('Esup_Common_Settings')
						->where('name', '=', 'logo_name')
						->find();
					$logo_name->value = $_FILES['logo']['name'];
					$logo_name->save();
					$this->cache_instance->delete(CP.'config_db_site');
					Upload::save($_FILES['logo'], $_FILES['logo']['name'], DOCROOT.$this->logo_path);
				}
				if ($_FILES['favicon']['error'] == 0)
				{
					Upload::save($_FILES['favicon'], $this->favicon_name, DOCROOT.$this->logo_path);
				}
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'Изменения сохранены.'
				));
			}
			catch (Exception $e)
			{
				$this->session->set('flash', array(
					'status' => 'error',
					'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
				));
			}
			$this->redirect('esup/settings/logo');
		}
		$this->template->content = View::factory('esup_pages/settings/logo')
			->set('model', $model)
			->set('logo', '/'.$this->logo_path.'/'.$this->config_db['logo_name'])
			->set('favicon', '/'.$this->logo_path.'/'.$this->favicon_name);
	}

	public function action_epay()
	{
		$model = ORM::factory($this->model_name);
		$path = DOCROOT.'private/kkb/prod';
		$zip_loaded = extension_loaded('zip');
		$test_mode = ORM::factory($this->model_name, array('name' => 'epay_test_mode'));
		if ($this->request->post('save') AND $zip_loaded)
		{
			try
			{
				if ($_FILES['archive']['error'] == 0)
				{
					$temp_file = Upload::save($_FILES['archive'], 'temp.zip', DOCROOT.'kkb');
					$this->rrmdir($path);
					mkdir($path, 0755, TRUE);
					$zip = new ZipArchive;
					$zip->open($temp_file);
					$zip->extractTo($path);
					$zip->close();
					if (is_file($temp_file))
					{
						unlink($temp_file);
					}
					$config_ini = parse_ini_file($path.'/config.txt');
					$config_array = array();
					foreach ($config_ini as $key => $item)
					{
						if ($key == 'XML_TEMPLATE_FN')
						{
							$config_array[$key] = DOCROOT.'kkb/template.xml';
						}
						elseif ($key == 'XML_COMMAND_TEMPLATE_FN')
						{
							$config_array['XML_TEMPLATE_CONFIRM_FN'] = DOCROOT.'kkb/template_confirm.xml';
						}
						elseif ($key == 'PRIVATE_KEY_FN' OR $key == 'PUBLIC_KEY_FN')
						{
							$config_array[$key] = $path.'/'.basename($item);
						}
						else
						{
							$config_array[$key] = $item;
						}
					}
					$config_array['PRIVATE_KEY_ENCRYPTED'] = 1;
					$this->write_ini_file($config_array, $path.'/config.txt');
				}
				$test_mode->value = (isset($_POST['test_mode'])) ? 1 : 0;
				$test_mode->save();
				$this->cache_instance->delete(CP.'config_db_site');
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'Изменения сохранены.'
				));
			}
			catch (Exception $e)
			{
				$this->session->set('flash', array(
					'status' => 'error',
					'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
				));
			}
			$this->redirect('esup/settings/epay');
		}
		$this->template->content = View::factory('esup_pages/settings/epay_kazkom')
			->set('model', $model)
			->set('zip_loaded', $zip_loaded)
			->set('test_mode', $test_mode->value);
	}

	public function action_sitemap()
	{
		$this->assets->pipeline->add('settings-sitemap-js');
		$model = ORM::factory($this->model_name);
		$site_protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === TRUE ? 'https' : 'http';
		$site_address = URL::base($site_protocol, TRUE);
		$languages = ORM::factory('Esup_Common_Language')
			->find_all();
		/* Генерация sitemap.xml */
		if (Arr::get($_GET, 'action') == 'generate')
			{
			try
			{
				if (class_exists('DomDocument') == FALSE)
				{
					throw new Exception('Класс DomDocument не найден.', 1);
				}
				$dom = new DomDocument('1.0', 'UTF-8');
				$urlset = $dom->appendChild($dom->createElement('urlset')); 
				$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
				$urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
				$urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
				/* Главная страница */
				$urlset = $this->append_sitemap_node($dom, $urlset, $site_address, $languages, TRUE);
				/* Текстовые страницы */
				foreach (ORM::factory('Esup_Page')->find_all() as $key => $item)
				{
					$urlset = $this->append_sitemap_node($dom, $urlset, $site_address.'ru/'.$item->link, $languages);
				}
				/* Статьи */
				foreach (ORM::factory('Esup_Article_Category')->order_by('sort', 'ASC')->find_all() as $key => $item)
				{
					foreach ($item->articles->order_by('creation_time', 'DESC')->find_all() as $key2 => $article)
					{
						$urlset = $this->append_sitemap_node($dom, $urlset, $site_address.'ru/a/'.$item->link.'/'.$article->link, $languages);
					}
				}
				$dom->formatOutput = TRUE;
				$raw_xml = $dom->saveXML();
				file_put_contents(DOCROOT.'sitemap.xml', $raw_xml);
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'sitemap.xml успешно сгенерирован.'
				));
			}
			catch (Exception $e)
			{
				$this->session->set('flash', array(
					'status' => 'error',
					'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
				));
			}
			$this->redirect('esup/settings/sitemap');
		}
		/* Пинг в поисковые системы */
		if (Arr::get($_GET, 'action') == 'ping')
			{
			try
			{
				Request::factory('http://www.google.com/webmasters/sitemaps/ping?sitemap='.$site_address.'sitemap.xml');
				Request::factory('http://webmaster.yandex.ru/wmconsole/sitemap_list.xml?host='.$site_address.'sitemap.xml');
				Request::factory('http://search.yahooapis.com/SiteExplorerServiceV1updateNotification?appid=SitemapWriter&url='.$site_address.'sitemap.xml');
				Request::factory('http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='.$site_address.'sitemap.xml');
				Request::factory('http://submissions.ask.com/ping?sitemap='.$site_address.'sitemap.xml');
				Request::factory('http://www.bing.com/webmaster/ping.aspx?siteMap='.$site_address.'sitemap.xml');
				$this->session->set('flash', array(
					'status' => 'ok',
					'message' => 'Пинг sitemap.xml прошел успешно.'
				));
			}
			catch (Exception $e)
			{
				$this->session->set('flash', array(
					'status' => 'error',
					'message' => $e->getMessage().'. Code: '.$e->getCode().'.'
				));
			}
			$this->redirect('esup/settings/sitemap');
		}
		if (is_file(DOCROOT.'sitemap.xml'))
		{
			$raw_sitemap = file_get_contents(DOCROOT.'sitemap.xml');
		}
		else
		{
			$raw_sitemap = 'Файл не создан.';
		}
		$this->template->content = View::factory('esup_pages/settings/sitemap')
			->set('model', $model)
			->set('raw_sitemap', $raw_sitemap);
	}

	private function get_css()
	{
	    if (is_file(DOCROOT.$this->css_full_name))
	    {
	        return file_get_contents(DOCROOT.$this->css_full_name);
	    }
	    else
	    {
	        $fp = fopen(DOCROOT.$this->css_full_name, 'w');
	        fclose($fp);
	    }
	}

	private function get_js()
	{
	    if (is_file(DOCROOT.$this->js_full_name))
	    {
	        return file_get_contents(DOCROOT.$this->js_full_name);
	    }
	    else
	    {
	        $fp = fopen(DOCROOT.$this->js_full_name, 'w');
	        fclose($fp);
	    }
	}

	private function rrmdir($dir)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
	 		foreach ($objects as $object)
	 		{
	    		if ($object != '.' AND $object != '..')
	    		{
	    			if (filetype($dir.'/'.$object) == 'dir')
	    			{
	    				$this->rrmdir($dir.'/'.$object);
	    			}
	    			else
    				{
    					unlink($dir.'/'.$object);
    				}
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	private function append_sitemap_node($dom, $urlset, $url_string, $languages, $force_no_lang = FALSE)
	{
		$url = $urlset->appendChild($dom->createElement('url'));
		$loc = $url->appendChild($dom->createElement('loc'));
		$loc->appendChild($dom->createTextNode($url_string));
		if ($force_no_lang)
		{
			return $urlset;
		}
		foreach ($languages as $key => $l)
		{
			$url = $urlset->appendChild($dom->createElement('url'));
			$loc = $url->appendChild($dom->createElement('loc'));
			$loc->appendChild($dom->createTextNode(str_replace('/ru/', '/'.$l->key.'/', $url_string)));
		}
		return $urlset;
	}

	private function write_ini_file($array, $path)
	{ 
		$content = "";
		foreach ($array as $key => $elem)
		{
			if (is_array($elem))
			{ 
				for ($i = 0; $i < count($elem); $i++)
				{
					$content .= $key."[] = \"".$elem[$i]."\"\r\n";
				}
			}
			elseif ($elem == "")
			{
				$content .= $key." = \r\n";
			}
			else
			{
				$content .= $key." = \"".$elem."\"\r\n";
			}
		}
		file_put_contents($path, $content);
	}

}