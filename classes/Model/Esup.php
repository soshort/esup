<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup extends ORM {

    static $postfix = '';
    static $cache_instance;

    protected $list_filtered = FALSE;

    public function get_prop($field) {
        return $this->{$field.self::$postfix};
    }

    /* Получить все элементы */
    public function get_items($limit = NULL, $offset = NULL, $sort_field = 'sort', $sort_direction = 'ASC') {
        $model = $this->order_by($sort_field, $sort_direction)
            ->order_by('id', 'DESC');
        if (isset($limit)) {
            $model = $model->limit($limit);
        }
        if (isset($offset)) {
            $model = $model->offset($offset);
        }
        $model = $model->find_all();
        return $model;
    }

    /*public function get_items_as_array($id = 'id', $title = 'title', $default = NULL) {
        if (empty($default)) {
            return $this->get_items()
                ->as_array($id, $title);
        } else {
            $result[NULL] = $default;
            return $result + $this->get_items()
                ->as_array($id, $title);
        }
    }*/

    /* Окончания по числу */
    public function ending($count, $one, $two, $many) {
        $tail = $count % 100;
        if ($tail > 20 || $tail < 5) {
            switch($tail % 10) {
                case 1:
                    $many = $one;
                    break;
                case 2:
                case 3:
                case 4:
                    $many = $two;
            }
        }
        return str_replace('@value@', $count, $many);
    }

    /* Возвращает форматированную дату */
    public function format_date($ts_field, $text, $lang) {
        $date_str = date('d F, Y', $this->$ts_field);
        $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $required_months = array($text['yanvar'], $text['fevral'], $text['mart'], $text['aprel'], $text['maj'], $text['iyun'], $text['iyul'], $text['avgust'], $text['sentyabr'], $text['oktyabr'], $text['noyabr'], $text['dekabr']);
        return str_replace($months, $required_months, date('d F, Y', $this->$ts_field));
    }

    /* Возвращает сслыку для внутренней страницы, лтбо на внешний ресурс */
    public function get_link($lang) {
        if ($this->page->loaded()) {
            return '/'.$lang.'/'.$this->page->link;
        } else {
            if (strpos($this->link, 'http://') === 0 || strpos($this->link, 'https://') === 0) {
                return $this->link;
            } elseif ($this->link == '/') {
                return '/'.$lang;
            } elseif (strpos($this->link, '#') === 0) {
                if (Request::$initial->controller() == 'Index') {
                    return $this->link;
                } else {
                    return '/'.$lang.$this->link;
                }
            } else {
                return '/'.$lang.'/'.$this->link;
            }
        }
    }

    /* Возвращает изображения для модели */
    public function get_images($table_name, $main = FALSE) {
        $images = $this->files->where('table_name', '=', $table_name);
        if ($main) {
            $images = $images->and_where('main', '=', 1)
                ->find();
            return ($images->loaded()) ? $images : $this->get_first_image($table_name);
        } else {
            return $images->find_all();
        }
    }

    /* Возвращает первое попавшееся изображение для модели */
    public function get_first_image($table_name) {
        return $this->files->where('table_name', '=', $table_name)
            ->order_by('sort', 'ASC')
            ->limit(1)
            ->find();
    }

    /* Получение данных вместе с привязанными галереями */
    public function get_prop_with_gallery($field, $view) {
        $text = $this->get_prop($field);
        preg_match_all('/\[gallery=&quot;([a-zA-Z-_]+)&quot;\]/', $text, $galleries);
        foreach ($galleries[1] as $key => $link) {
            $gallery = ORM::factory('Esup_Gallery', array('link' => $link));
            $gallery_html = $view->set('gallery', $gallery)
                ->render();
            $text = str_replace('[gallery=&quot;'.$link.'&quot;]', $gallery_html, $text);
        }
        return $text;
    }

    /* Заполнение модели данными из формы */
    public function fill($fields) {
        foreach ($fields as $key => $item) {
            $post_item = Arr::get($_POST, $key);
            switch ($item['type']) {
                case 'uuid':
                    if ($post_item) {
                        $this->$key = $post_item;
                    } else {
                        $q = DB::select(array(DB::expr('UUID()'), 'uuid'))
                            ->execute()
                            ->current();
                        $this->$key = $q['uuid'];
                    }
                break;
                case 'belongs_to':
                    if ($post_item) {
                        $this->$key = $post_item;
                    } else {
                        $this->$key = NULL;
                    }
                break;
                case 'date':
                    $this->$key = (empty($post_item)) ? time() : strtotime($post_item);
                break;
                case 'checkbox':
                    $this->$key = (isset($_POST[$key])) ? 1 : 0;
                break;
                case 'password':
                    if (isset($_POST[$key])) {
                        $this->$key = md5($_POST[$key]);
                    } else {
                        $p = $this->$key;
                        if (empty($p)) {
                            $this->$key = '';
                        }
                    }
                break;
                default:
                    /* Транслитерация полей, если настройка не пуста и если текущий элемент массива $_POST пуст */
                    if (isset($item['transliterate']) && empty($post_item)) {
                        $translit = $this->transliterate(Arr::get($_POST, $item['transliterate']['from_field']));
                        $this->$key = $translit;
                        if (isset($item['transliterate']['unique'])) {
                            $this->$key .= '-'.Text::random('alpha', 8);
                        }
                    /* Иначе просто забираем данные из $_POST */
                    } else {
                        if (isset($item['translate']) && $item['translate'] == TRUE) {
                            $this->{$key.self::$postfix} = $post_item;
                        } else {
                            $this->$key = $post_item;
                        }
                        /* Обработка полей с HTML тегами, если TRUE */
                        /*if (isset($item['code'])) {
                            $this->{$key.self::$postfix} = html_entity_decode($this->$key, ENT_QUOTES, 'UTF-8');
                        }*/
                    }
                break;
            }
        }
        //return $this;
    }

    /* Заполнение много ко многим */
    public function save_many_to_many() {
        if (isset($this->options['many_to_many'])) {
            foreach ($this->options['many_to_many'] as $key => $item) {
                $this->set_many_to_many($key);
            }
        }
    }

    private function set_many_to_many($option) {
        $this->remove($option);
        if (isset($_POST[$option])) {
            foreach ($_POST[$option] as $key => $item) {
                $related_model = ORM::factory($this->_has_many[$option]['model'], $item);
                $this->add($option, $related_model);
            }
        }
    }

    /* Получение данных для формы select */
    public function get_array_for_select($default = FALSE, $id_field = 'id', $title_field = 'title', $order_field = 'id', $order_direction = 'ASC') {
        $res = DB::select('id', $title_field)
            ->from($this->_table_name)
            ->order_by($order_field, $order_direction)
            ->execute()
            ->as_array($id_field, $title_field);
        if ($default === FALSE) {
            return $res;
        } else {
            return array(NULL => 'Нет') + $res;
        }
    }

    /* Транслитерация */
    public static function transliterate($str) {
        return strtr(mb_strtolower($str), array(
            'а' => 'a',     'б' => 'b',  'в' => 'v',   'г' => 'g',  'д' => 'd',
            'е' => 'e',     'ж' => 'zh', 'з' => 'z',   'и' => 'i',  'й' => 'j',
            'к' => 'k',     'л' => 'l',  'м' => 'm',   'н' => 'n',  'о' => 'o',
            'п' => 'p',     'р' => 'r',  'с' => 's',   'т' => 't',  'у' => 'u',
            'ё' => 'yo',    'х' => 'h',  'ц' => 'ts',  'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'shch',  'ъ' => '',   'ь' => '',    'ю' => 'yu', 'я' => 'ya',
            'ф' => 'f',     'э' => 'e',  'ы' => 'i',   '_' => '-', ' ' => '-',
            'А' => 'a',     'Б' => 'b',  'В' => 'v',   'Г' => 'g',  'Д' => 'd',
            'Е' => 'e',     'Ж' => 'zh', 'З' => 'z',   'И' => 'i',  'Й' => 'j',
            'К' => 'k',     'Л' => 'l',  'М' => 'm',   'Н' => 'n',  'О' => 'o',
            'П' => 'p',     'Р' => 'r',  'С' => 's',   'Т' => 't',  'У' => 'u',
            'Ё' => 'yo',    'Х' => 'h',  'Ц' => 'ts',  'Ч' => 'ch', 'Ш' => 'sh',
            'Щ' => 'shch',  'Ъ' => '',   'Ь' => '',    'Ю' => 'yu', 'Я' => 'ya',
            'Ф' => 'f',     'Э' => 'e',  'Ы' => 'i',   ':' => '',   '-' => '-',
            '.' => '',      '?' => '',   '(' => '',   ')' => '',   '<' => '',
            '>' => '',      '"' => '',   '\'' => '',   '*' => '',   '$' => '',
            '@' => '',      '%' => '',   '^' => '',   '&' => '',   '+' => '',
            '=' => '',      '\\' => '',   '|' => '',   '/' => '',   ',' => '',
            '~' => '',      '«' => '',   '»' => '',     ';' => '', '#' => '',
            '!' => '',      '№' => '',   '[' => '',     '{' => '',  '}' => '',
            ']' => '',
        ));
    }

    /* Загрузка файлов */
    public function save_files() {
        if (empty($this->options['files'])) {
            return FALSE;
        }
        foreach ($this->options['files'] as $field_key => $field) {
            $files = array();
            if (isset($field['multiple'])) {
                $files =  ORM::factory('Esup_Common_File')
                    ->array_correction($_FILES[$field_key]);
            } else {
                $files[] = $_FILES[$field_key];
            }
            $i = 0;
            foreach ($files as $file_key => $file) {
                /* Если файл не загружен, пропускаем итерацию */
                if ($file['error'] != 0) {
                    continue;
                }
                /*
                 Если файлы добавляются к записи впервые после создания,
                 то помечаем первый из них как основной
                */
                if (isset($_POST['add']) && $i == 0) {
                    $file_main_lable = 1;
                } else {
                    $file_main_lable = 0;
                }
                $file = ORM::factory('Esup_Common_File')->save_file($file, $field_key, $this->id, $file_main_lable);
                /* Создаем thumbnails, если они указаны в параметрах модели */
                if (isset($field['thumbnails'])) {
                    foreach ($field['thumbnails'] as $thumbnail_key => $thumbnail) {
                        $file->save_thumbnail($thumbnail);
                    }
                }
                /* Удаляем оригинал изображения, если указано в параметрах */
                if (isset($field['remove_original']) && $field['remove_original'] === TRUE) {
                    $file->delete_file(TRUE);
                }
                $i++;
            }
        }
        return TRUE;
    }

    /* Удаление файлов */
    public function delete_files() {
        if (empty($this->options['files'])) {
            return FALSE;
        }
        foreach ($this->options['files'] as $field_key => $field) {
            $files = ORM::factory('Esup_Common_File')
                ->where('table_name', '=', $field_key)
                ->and_where('item_id', '=', $this->id)
                ->find_all();
            foreach ($files as $file_key => $file) {
                $file->delete_file();
            }
        }
    }

    /* Возвращает хлебные крошки для модели */
    public function breadcrumbs($id, $parent_field = 'parent_id', $title_field = 'title', $result = array()) {
        if (isset($id)) {
            $db = DB::select('id', $parent_field, $title_field)
                ->from($this->table_name())
                ->where('id', '=', $id)
                ->as_object()
                ->execute()
                ->current();
            $result[] = array('id' => $db->id, 'title' => $db->$title_field);
            if ($db->$parent_field == NULL) {
                return array_reverse($result);
            } else {
                return $this->breadcrumbs($db->$parent_field, $parent_field, $title_field, $result);
            }
        } else {
            return $result;
        }
    }

    /* Устанавливает смещение для постраничности */
    public function set_offset($page, $limit) {
        if ($page == 1 || empty($page)) {
            $this->offset(0);
        } else {
            $this->offset($page * $limit - $limit);
        }
        return $this;
    }

    /* Возвращает массив id для указанной многоуровневой модели */
    public function get_ids_tree($model, $relation_name = 'parent', $ids_arr = array()) {
        $ids_arr[] = $model->id;
        if ($model->{$relation_name}->loaded()) {
            return $this->get_ids_tree($model->{$relation_name}, $relation_name, $ids_arr);
        } else {
            return $ids_arr;
        }
    }

    /* Фильтрация */
    public function apply_filters() {
        foreach ($this->options['filters'] as $filter_key => $filter) {
            $filter_value = Arr::get($_GET, $filter_key);
            if (empty($filter_value)) {
                continue;
            }
            if ($this->list_filtered == FALSE) {
                $this->list_filtered = TRUE;
                $this->where_open();
            }
            if (is_array($filter['fields'])) {
                if ($filter_key == 0) {
                    $this->where_open();
                } else {
                    $this->and_where_open();
                }
                foreach ($filter['fields'] as $filter_field_key => $filter_field) {
                    if ($filter_field_key == 0) {
                        $this->where($filter_field, 'LIKE', '%'.$filter_value.'%');
                    } else {
                        $this->or_where($filter_field, 'LIKE', '%'.$filter_value.'%');
                    }
                }
                $this->where_close();
            } else {
                if ($filter['type'] == 'text') {
                    $this->where($filter['fields'], 'LIKE', '%'.$filter_value.'%');
                } else {
                    $this->where($filter['fields'], '=', $filter_value);
                }
            }
        }
        if ($this->list_filtered) {
            $this->where_close();
        }
        return $this;
    }

    /* Делает выборку записей только для выбранного уровня */
    public function apply_tree_structure($tree_structure) {
        /* Если действует фильтрация, то делаем выборку по всем записям */
        if ($this->list_filtered) {
            return $this;
        }
        $parent_id = Arr::get($_GET, $tree_structure['field']);
        if ($parent_id) {
            $this->where($tree_structure['field'], '=', $parent_id);
        } else {
            $this->where($tree_structure['field'], '=', NULL);
        }
        return $this;
    }

}