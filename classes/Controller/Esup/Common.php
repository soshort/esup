<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Esup_Common extends Controller_Template {

    public $cache_instance;
    public $template = 'esup_layout/main';
    public $session;
    public $config_esup;
    public $config_db;
    public $url_query;
    public $admin;
    public $access_level = 0;
    public $lang;
    public $lang_default = array(
        'key' => 'ru',
        'title' => 'Русский',
        'visible_name' => 'Рус'
    );
    public $postfix;

    public function before() {
        parent::before();
        if (Cookie::get('admin', FALSE) == FALSE) {
            $this->redirect('esup/auth');
        } else {
            $this->admin = ORM::factory('Esup_Common_Administrator', Cookie::get('admin'));
        }
        if ($this->admin->access_level < $this->access_level) {
            throw new HTTP_Exception_404();
        }
        $this->cache_instance = Cache::instance(CACHE_DRIVER);
        //$this->cache_instance->delete_all();
        $this->session = Session::instance();
        /* Языки */
        $lang_instance = ORM::factory('Esup_Common_Language')
            ->get_instance($this->session->get('lang'), $this->lang_default['key']);
        $this->lang = $lang_instance->get_lang();
        $this->postfix = $lang_instance->get_postfix();
        /* Окружение для основной модели */
        Model_Esup::$postfix = $this->postfix;
        Model_Esup::$cache_instance = $this->cache_instance;
        /* Конфиги */
        $this->config_db = ORM::factory('Esup_Common_Settings')
            ->get_config_db();
        $this->config_esup = Kohana::$config->load('esup');
        /* URL */
        $this->url_query = URL::query();
        View::set_global('session', $this->session);
        View::set_global('lang', $this->lang);
        View::set_global('url_query', $this->url_query);
        View::set_global('admin', $this->admin);
        View::set_global('title', 'ESUP - Админ панель');
        $top_menu = $this->config_esup->get('top_menu');
        $languages = ORM::factory('Esup_Common_Language')
            ->get_active($this->lang_default);
        $this->template->header = View::factory('esup_pieces/header')
            ->set('top_menu', $top_menu)
            ->set('languages', $languages);
        $this->template->content = '';
        $this->template->footer = View::factory('esup_pieces/footer');
    }

}