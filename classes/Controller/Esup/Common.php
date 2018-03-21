<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Esup_Common extends Controller_Template {

    public $template = 'esup_layout/main';

    protected $session;
    protected $config_esup;
    protected $config_db;
    protected $url_query;
    protected $admin;
    protected $languages;
    protected $lang;
    protected $postfix;
    protected $access_level = 0;

    public function before()
    {
        parent::before();
        /*$cache_instance = Cache::instance(CACHE_DRIVER);
        $cache_instance->delete_all();*/
        /* Get current user model */
        if ( ! Cookie::get('admin', FALSE))
        {
            $this->redirect('esup/auth');
        }
        else
        {
            $this->admin = ORM::factory('Esup_Common_Administrator', Cookie::get('admin'));
        }
        /* Throw 401 if the user does not have access to requested section */
        if ($this->admin->access_level < $this->access_level)
        {
            throw new HTTP_Exception_401();
        }
        /* Load ESUP config */
        $this->config_esup = Kohana::$config->load('esup');
        /* Get config from database */
        $this->config_db = ORM::factory('Esup_Common_Settings')
            ->get_config_db();
        /* Get session instance */
        $this->session = Session::instance();
        /* Define active languages */
        $lang_instance = ORM::factory('Esup_Common_Language')
            ->get_instance($this->session->get('lang'));
        $this->lang = $lang_instance->get_lang();
        $this->postfix = $lang_instance->get_postfix();
        $this->languages = ORM::factory('Esup_Common_Language')
            ->get_active();
        /* Define language environment for root model */
        Model_Esup::$postfix = $this->postfix;
        /* Define URL query */
        $this->url_query = URL::query();
        /* Define global variables and objects for views */
        View::set_global(array(
            'session' => $this->session,
            'lang' => $this->lang,
            'url_query' => $this->url_query,
            'admin' => $this->admin,
            'title' => 'ESUP - Панель управления'
        ));
        /* Load assets config */
        $assets_config = Kohana::$config->load('esup_assets');
        /* Set assets */
        $this->assets = new stdClass;
        $this->assets->single = new \Stolz\Assets\Manager($assets_config->get('single'));
        $this->assets->pipeline = new \Stolz\Assets\Manager($assets_config->get('pipeline'));
        $this->template->assets = $this->assets;
        /* Set header */
        $this->template->header = View::factory('esup_pieces/header')
            ->set('top_menu', $this->config_esup->get('top_menu'))
            ->set('languages', $this->languages);
        /* Set body */
        $this->template->content = '';
        /* Set footer */
        $this->template->footer = View::factory('esup_pieces/footer');
    }

}