<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Esup_Common extends Controller_Template {

    public $template = 'esup_layout/main';
    public $session;
    public $config_esup;
    public $config_db;
    public $url_query;
    public $admin;
    public $access_level = 0;
    public $languages;
    public $lang;
    public $postfix;

    public function before() {
        parent::before();
        /*$cache_instance = Cache::instance(CACHE_DRIVER);
        $cache_instance->delete_all();*/
        if (Cookie::get('admin', FALSE) == FALSE) {
            $this->redirect('esup/auth');
        } else {
            $this->admin = ORM::factory('Esup_Common_Administrator', Cookie::get('admin'));
        }
        if ($this->admin->access_level < $this->access_level) {
            throw new HTTP_Exception_404();
        }
        /* Настройки из основного файла конфигурации */
        $this->config_esup = Kohana::$config->load('esup');
        /* Настройки из БД */
        $this->config_db = ORM::factory('Esup_Common_Settings')
            ->get_config_db();
        /* Сессия */
        $this->session = Session::instance();
        /* Языки */
        $lang_instance = ORM::factory('Esup_Common_Language')
            ->get_instance($this->session->get('lang'));
        $this->lang = $lang_instance->get_lang();
        $this->postfix = $lang_instance->get_postfix();
        $this->languages = ORM::factory('Esup_Common_Language')
            ->get_active();
        /* Окружение для основной модели */
        Model_Esup::$postfix = $this->postfix;
        /* URL */
        $this->url_query = URL::query();
        View::set_global(array(
            'session' => $this->session,
            'lang' => $this->lang,
            'url_query' => $this->url_query,
            'admin' => $this->admin,
            'title' => 'ESUP - Админ панель'
        ));
        $top_menu = $this->config_esup->get('top_menu');
        $this->template->header = View::factory('esup_pieces/header')
            ->set('top_menu', $top_menu)
            ->set('languages', $this->languages);
        $this->template->content = '';
        $this->template->footer = View::factory('esup_pieces/footer');
    }

}