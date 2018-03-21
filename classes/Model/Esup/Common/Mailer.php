<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Mailer extends Model_Esup {

    private $config;
    private $config_options;
    private $email_stack = array();

    protected $_table_name = 'mailer_queue';

    public $options = array(
        'filters' => array(
            'search_query' => array(
                'type' => 'text',
                'fields' => array('_from', '_to', 'subject'), // Может быть массивом, например: array('title', 'text')
                'render' => array(
                    'title' => 'Поиск'
                )
            )
        ),
        'render' => array(
            'title' => 'Письма в очереди',
            'link' => 'mailer'
        )
    );

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        $this->config = Kohana::$config->load('email');
        $this->config_options = $this->config->get('options');
    }

    /* Отправить все письма со статусом "не отправлено" */
    public function send($limit = NULL)
    {
        Email::connect($this->config);
        if ($this->loaded())
        {
            $this->email_stack[] = $this;
        }
        if (empty($this->email_stack))
        {
            $items = $this->where('status', '!=', 1)
                ->order_by('id', 'ASC')
                ->limit($limit)
                ->find_all();
        }
        else
        {
            $items = $this->email_stack;
        }
        $total = count($items);
        $sended = 0;
        foreach ($items as $key => $item)
        {
            try
            {
                if ($item->_to[0] == '{' OR $item->_to[0] == '[')
                {
                    $to = json_decode($item->_to, TRUE);
                }
                else
                {
                    $to = $item->_to;
                }
                Email::send($to, $this->config_options['username'], $item->subject, $item->message, $html = TRUE);
                $item->status = 1;
                $item->_from = $this->config_options['username'];
                $item->mailer_response = 'ok';
                $item->save();
                $sended++;
            }
            catch (Exception $e)
            {
                $item->status = 2;
                $item->_from = $this->config_options['username'];
                $item->mailer_response = $e->getMessage();
                $item->save();
            }
        }
        return array('total' => $total, 'sended' => $sended);
    }

    private function _send($item)
    {
        try
        {
            if ($item->_to[0] == '{' OR $item->_to[0] == '[')
            {
                $to = json_decode($item->_to, TRUE);
            }
            else
            {
                $to = $item->_to;
            }
            Email::send($to, $this->config_options['username'], $item->subject, $item->message, $html = TRUE);
            $item->status = 1;
            $item->mailer_response = 'ok';
            $item->_from = $this->config_options['username'];
            $item->save();
            return TRUE;
        }
        catch (Exception $e)
        {
            $item->status = 2;
            $item->mailer_response = $e->getMessage();
            $item->_from = $this->config_options['username'];
            $item->save();
            return FALSE;
        }
    }

    public function add_email($options)
    {
        if (isset($options['to']))
        {
            if (is_array($options['to']))
            {
                $options['to'] = json_encode($options['to']);
            }
        }
        else
        {
            $options['to'] = $this->config_options['inbox'];
        }
        $email = ORM::factory('Esup_Common_Mailer');
        $email->_from = $this->config_options['username'];
        $email->_to = $options['to'];
        $email->subject = $options['subject'];
        $email->message = $options['message'];
        $email->status = 0;
        $email->save();
        $this->email_stack[] = $email;
        return $this;
    }

}