<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup extends ORM {

    public static $postfix = '';

    protected $list_filtered = FALSE;

    /* Возвращает поле, основанное на выбранном языке */
    public function get_prop($field)
    {
        return $this->{$field.self::$postfix};
    }

    /* Получить все элементы */
    public function get_items($limit = NULL, $offset = NULL, $sort_field = 'sort', $sort_direction = 'ASC')
    {
        $this->order_by($sort_field, $sort_direction)
            ->order_by('id', 'DESC');
        if (isset($limit))
        {
            $this->limit($limit);
        }
        if (isset($offset))
        {
            $this->offset($offset);
        }
        return $this->find_all();
    }

    /* Устанавливает смещение для постраничности */
    public function set_offset($page, $limit)
    {
        if ($page == 1 OR empty($page))
        {
            $this->offset(0);
        }
        else
        {
            $this->offset($page * $limit - $limit);
        }
        return $this;
    }

    /* Окончания по числу */
    public function ending($count, $one, $two, $many)
    {
        $tail = $count % 100;
        if ($tail > 20 OR $tail < 5)
        {
            switch($tail % 10)
            {
                case 1:
                    $many = $one;
                break;
                case 2:
                case 3:
                case 4:
                    $many = $two;
                break;
            }
        }
        return str_replace('@value@', $count, $many);
    }

    /* Возвращает форматированную дату */
    public function format_date($ts_field, $text, $format = 'd F, Y')
    {
        $search = array(
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        );
        $replace = array(
            $text['january'],
            $text['february'],
            $text['march'],
            $text['april'],
            $text['may'],
            $text['june'],
            $text['july'],
            $text['august'],
            $text['september'],
            $text['october'],
            $text['november'],
            $text['december']
        );
        return mb_strtolower(str_ireplace($search, $replace, date($format, $this->$ts_field)));
    }

    /* Возвращает сслыку для внутренней страницы, либо на внешний ресурс */
    public function get_link($lang)
    {
        if ($this->page->loaded())
            return '/'.$lang.'/'.$this->page->link;
        else
        {
            if (strpos($this->link, 'http://') === 0 OR strpos($this->link, 'https://') === 0)
                return $this->link;
            elseif ($this->link == '/')
                return '/'.$lang;
            elseif (strpos($this->link, '#') === 0)
            {
                if (Request::$initial->controller() == 'Index')
                    return $this->link;
                else
                    return '/'.$lang.$this->link;
            }
            else
                return '/'.$lang.'/'.$this->link;
        }
    }

    /* Возвращает связанные с моделью файлы */
    public function get_files($group_name)
    {
        return $this->files->where('group_name', '=', $group_name)
            ->find_all();
    }

    /* Возвращает основной файл для модели (или первый попавшийся, если основной не указан) */
    public function get_file($group_name)
    {
        $file = $this->files->where('group_name', '=', $group_name)
            ->and_where('main', '=', 1)
            ->find();
        if ($file->loaded())
            return $file;
        else
            return $this->get_first_file($group_name);
    }

    /* Возвращает первый попавшийся файл для модели */
    public function get_first_file($group_name)
    {
        return $this->files->where('group_name', '=', $group_name)
            ->order_by('sort', 'ASC')
            ->limit(1)
            ->find();
    }

    /* Получение данных вместе с привязанными галереями */
    public function get_prop_with_gallery($field, $view)
    {
        $text = $this->get_prop($field);
        preg_match_all('/\[gallery=&quot;([a-zA-Z-_]+)&quot;\]/', $text, $galleries);
        foreach ($galleries[1] as $key => $link)
        {
            $gallery = ORM::factory('Esup_Gallery', array('link' => $link));
            $gallery_html = $view->set('gallery', $gallery)
                ->render();
            $text = str_replace('[gallery=&quot;'.$link.'&quot;]', $gallery_html, $text);
        }
        return $text;
    }

    /* Заполнение модели данными из формы */
    public function fill($fields)
    {
        foreach ($fields as $key => $item)
        {
            if (isset($item['edit']) AND $item['edit'] == FALSE)
                continue;
            $post_item = Arr::get($_POST, $key);
            switch ($item['type'])
            {
                case 'uuid':
                    if ($post_item)
                    {
                        $this->$key = $post_item;
                    }
                    else
                    {
                        $q = DB::select(array(DB::expr('UUID()'), 'uuid'))
                            ->execute()
                            ->current();
                        $this->$key = $q['uuid'];
                    }
                break;
                case 'select2':
                    if ($post_item)
                    {
                        $this->$key = $post_item;
                    }
                    else
                    {
                        $this->$key = NULL;
                    }
                break;
                case 'belongs_to_text':
                    if ($post_item)
                    {
                        $this->$key = $post_item;
                    }
                    else
                    {
                        $this->$key = NULL;
                    }
                break;
                case 'date':
                    if (empty($post_item))
                    {
                        $this->$key = time();
                    }
                    else
                    {
                        $this->$key = strtotime($post_item);
                    }
                break;
                case 'checkbox':
                    if (isset($_POST[$key]))
                    {
                        $this->$key = 1;
                    }
                    else
                    {
                        $this->$key = 0;
                    }
                break;
                case 'password':
                    if (isset($_POST[$key]))
                    {
                        $this->$key = md5($_POST[$key]);
                    }
                    else
                    {
                        $p = $this->$key;
                        if (empty($p))
                        {
                            $this->$key = '';
                        }
                    }
                break;
                default:
                    /* Транслитерация полей, если настройка не пуста и если текущий элемент массива $_POST пуст */
                    if (isset($item['transliterate']) AND empty($post_item))
                    {
                        $translit = $this->transliterate(Arr::get($_POST, $item['transliterate']['from_field']));
                        $this->$key = $translit;
                        if (isset($item['transliterate']['unique']))
                        {
                            $this->$key .= '-'.Text::random('alpha', 8);
                        }
                    /* Иначе просто забираем данные из $_POST */
                    }
                    else
                    {
                        if (isset($item['translate']) AND $item['translate'] == TRUE)
                        {
                            $this->{$key.self::$postfix} = $post_item;
                        }
                        else
                        {
                            $this->$key = $post_item;
                        }
                    }
                break;
            }
        }
    }

    /* Заполнение Списки */
    public function save_many_to_many()
    {
        if (isset($this->options['many_to_many']))
        {
            foreach ($this->options['many_to_many'] as $key => $item)
            {
                $this->set_many_to_many($key);
            }
        }
    }

    /* Транслитерация */
    public static function transliterate($str)
    {
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
            ']' => ''
        ));
    }

    /* Загрузка файлов */
    public function save_files()
    {
        foreach (Arr::get($this->options, 'files', array()) as $group_name => $group)
        {
            $files = array();
            if (Arr::get($group, 'multiple'))
            {
                $files =  ORM::factory('Esup_Common_File')
                    ->array_correction($_FILES[$group_name]);
            }
            else
            {
                $files[] = $_FILES[$group_name];
            }
            $i = 0;
            foreach ($files as $file_key => $file)
            {
                /* Если файл не загружен, пропускаем итерацию */
                if ($file['error'] > 0)
                    continue;
                /*
                 Если файлы добавляются к записи впервые после создания,
                 то помечаем первый из них как основной
                */
                if (isset($_POST['add']) AND $i == 0)
                {
                    $main_file = 1;
                }
                else
                {
                    $main_file = 0;
                }
                $file = ORM::factory('Esup_Common_File')
                    ->save_file($file, $group_name, $this->id, $main_file);
                /* Создаем thumbnails, если они указаны в параметрах модели */
                foreach (Arr::get($group, 'thumbnails', array()) as $thumbnail_key => $thumbnail)
                {
                    $file->thumbnail($thumbnail);
                }
                $i++;
            }
            /* Crop images */
            foreach (Arr::get($_POST, 'crop', array()) as $key => $files_to_crop)
            {
                foreach ($files_to_crop as $file_id => $json_string)
                {
                    if (empty($json_string))
                        continue;
                    ORM::factory('Esup_Common_File', $file_id)
                        ->crop_image($group, $json_string);
                }
            }
        }
    }

    /* Удаление файлов */
    public function delete_files()
    {
        if (empty($this->options['files']))
        {
            return FALSE;
        }
        foreach ($this->options['files'] as $group_name => $group)
        {
            $files = ORM::factory('Esup_Common_File')
                ->where('group_name', '=', $group_name)
                ->and_where('item_id', '=', $this->id)
                ->find_all();
            foreach ($files as $file_key => $file)
            {
                $file->delete_file();
            }
        }
    }

    /* Возвращает хлебные крошки для модели */
    public function breadcrumbs($id, $parent_field = 'parent_id', $title_field = 'title', $result = array())
    {
        if (empty($id) == FALSE)
        {
            $db = DB::select('id', $parent_field, $title_field)
                ->from($this->table_name())
                ->where('id', '=', $id)
                ->as_object()
                ->execute()
                ->current();
            /* Return an empty array if record not found */
            if (empty($db))
                return $result;
            $result[] = array('id' => $db->id, 'title' => $db->$title_field);
            if ($db->$parent_field == NULL)
                return array_reverse($result);
            else
                return $this->breadcrumbs($db->$parent_field, $parent_field, $title_field, $result);
        }
        else
            return $result;
    }

    /* Возвращает массив id для указанной многоуровневой модели */
    public function get_ids_tree($model, $relation_name = 'parent', $ids_arr = array())
    {
        $ids_arr[] = $model->id;
        if ($model->{$relation_name}->loaded())
            return $this->get_ids_tree($model->{$relation_name}, $relation_name, $ids_arr);
        else
            return $ids_arr;
    }

    /* Фильтрация */
    public function apply_filters()
    {
        foreach ($this->options['filters'] as $filter_key => $filter)
        {
            $filter_value = Arr::get($_GET, $filter_key);
            if (empty($filter_value))
                continue;
            if ($this->list_filtered == FALSE)
            {
                $this->list_filtered = TRUE;
                $this->where_open();
            }
            if (is_array($filter['fields']))
            {
                if ($filter_key == 0)
                {
                    $this->where_open();
                }
                else
                {
                    $this->and_where_open();
                }
                foreach ($filter['fields'] as $filter_field_key => $filter_field)
                {
                    if ($filter_field_key == 0)
                    {
                        $this->where($filter_field, 'LIKE', '%'.$filter_value.'%');
                    }
                    else
                    {
                        $this->or_where($filter_field, 'LIKE', '%'.$filter_value.'%');
                    }
                }
                $this->where_close();
            }
            else
            {
                if ($filter['type'] == 'text')
                {
                    $this->where($filter['fields'], 'LIKE', '%'.$filter_value.'%');
                }
                else
                {
                    $this->where($filter['fields'], '=', $filter_value);
                }
            }
        }
        if ($this->list_filtered)
        {
            $this->where_close();
        }
        return $this;
    }

    /* Делает выборку записей только для выбранного уровня */
    public function apply_tree_structure($tree_structure)
    {
        /* Если действует фильтрация, то делаем выборку по всем записям */
        if ($this->list_filtered)
            return $this;
        $parent_id = Arr::get($_GET, $tree_structure['field']);
        if ($parent_id)
        {
            $this->where($tree_structure['field'], '=', $parent_id);
        }
        else
        {
            $this->where($tree_structure['field'], '=', NULL);
        }
        return $this;
    }

    private function set_many_to_many($option)
    {
        $this->remove($option);
        if (isset($_POST[$option]))
        {
            foreach ($_POST[$option] as $key => $item)
            {
                $related_model = ORM::factory($this->_has_many[$option]['model'], $item);
                $this->add($option, $related_model);
            }
        }
    }

}