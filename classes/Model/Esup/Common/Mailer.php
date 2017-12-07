<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_Mailer extends Model_Esup {

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

    /* Выполняет очередь */
    public function send_all($limit = NULL) {
        $config = Kohana::$config->load('email');
        $options = $config->get('options');
        $from = $options['username'];
        Email::connect($config);
        $rows = DB::select()
            ->from($this->_table_name)
            ->where('status', '!=', 1)
            ->order_by('id', 'ASC')
            ->limit($limit)
            ->as_object()
            ->execute();
        $total = count($rows);
        $sended = 0;
        foreach ($rows as $key => $item) {
            try {
                if ($item->_to[0] == '{' || $item->_to[0] == '[') {
                    $to = json_decode($item->_to, TRUE);
                } else {
                    $to = $item->_to;
                }
                Email::send($to, $from, $item->subject, $item->message, $html = TRUE);
                DB::update($this->_table_name)
                    ->set(array('status' => 1, 'mailer_response' => 'ok', '_from' => $from))
                    ->where('id', '=', $item->id)
                    ->execute();
                $sended++;
            } catch (Exception $e) {
                DB::update($this->_table_name)
                    ->set(array('status' => 2, 'mailer_response' => $e->getMessage(), '_from' => $from))
                    ->where('id', '=', $item->id)
                    ->execute();
            }
        }
        return array('total' => $total, 'sended' => $sended);
    }

    public function send() {
        $config = Kohana::$config->load('email');
        $options = $config->get('options');
        $from = $options['username'];
        Email::connect($config);
        try {
            if ($this->_to[0] == '{' || $this->_to[0] == '[') {
                $to = json_decode($this->_to, TRUE);
            } else {
                $to = $this->_to;
            }
            Email::send($to, $from, $this->subject, $this->message, $html = TRUE);
            $this->status = 1;
            $this->mailer_response = 'ok';
            $this->_from = $from;
            $this->save();
        } catch (Exception $e) {
            $this->status = 2;
            $this->mailer_response = $e->getMessage();
            $this->_from = $from;
            $this->save();
        }
        return array('total' => 1, 'sended' => 1);
    }

    public function save_email($from, $to, $subject, $message) {
        if (is_array($to)) {
            $to = json_encode($to);
        }
        $this->_from = $from;
        $this->_to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->status = 0;
        $this->save();
        return $this;
    }

}