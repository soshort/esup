<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_File extends Model_Esup {

    protected $_table_name = 'files';

    public $file_path;
    public $file_path_unsigned;
    public $file_name;
    public $file_extension;
    public $full_name;
    public $img_extensions = array('jpg', 'png', 'jpeg', 'gif', 'bmp');
    public $file_path_info = array();
    public $options = array(
        'fields' => array(
            'description' => array(
                'label' => 'Описание',
                'type' => 'text',
                'translate' => TRUE,
            ),
        ),
        'filters' => array(
            'search_query' => array(
                'type' => 'text',
                'fields' => array('file', 'original_name'), // Может быть массивом, например: array('title', 'text')
                'render' => array(
                    'title' => 'Имя файла'
                )
            )
        ),
        'render' => array(
            'form' => TRUE,
            'title' => 'Прикрепленные к записям файлы',
            'link' => 'files',
        )
    );

    public function __construct($id = NULL) {
        parent::__construct($id);
        $this->file_path = Kohana::$config->load('esup.files_dir');
        if (is_dir($this->file_path) == FALSE) {
            mkdir($this->file_path, 0755, TRUE);
        }
    }

    public function set_path($path) {
        $this->file_path = $path;
        return $this;
    }

    /* Сохранение файла */
    public function save_file($file, $table_name, $item_id, $file_main_lable) {
        $this->file_path_info = pathinfo($file['name']);
        $this->file_name = md5(md5_file($file['tmp_name']).time());
        $this->file_extension = strtolower($this->file_path_info['extension']);
        $this->full_name = Upload::save($file, $this->file_name.'.'.$this->file_extension, $this->file_path);
        if ($this->full_name == FALSE) {
            return FALSE;
        }
        /* Если файл является изображением, то пережимаем его */
        if ($this->is_image($this->file_extension)) {
            /* Если размер изображения больше 500кб, сохраняем как .jpg */
            if ($file['size'] > 512000) {
                $image = Image::factory($this->full_name);
                $image->save(str_replace('.'.$this->file_extension, '.jpg', $image->file), 80);
                /* Если файл не является .jpg, то удаляем оригинал изображения */
                if ($this->file_extension != 'jpg') {
                    if (is_file($this->full_name)) {
                        unlink($this->full_name);
                    }
                }
                $this->file_extension = 'jpg';
                $this->full_name = str_replace('.'.$this->file_path_info['extension'], '.jpg', $image->file);
            }
        }
        $this->table_name = $table_name;
        $this->item_id = $item_id;
        $this->file = $this->file_name.'.'.$this->file_extension;
        $this->original_name = $file['name'];
        $this->extension = $this->file_extension;
        $this->creation_time = time();
        $this->main = $file_main_lable;
        $this->save();
        return $this;
    }

    /* Сохранение файла из указанного URL */
    public function save_file_from_url($url, $table_name, $item_id, $file_main_lable) {
        $raw_data = file_get_contents($url);
        $original_name = basename(parse_url($url, PHP_URL_PATH));
        $this->file_path_info = pathinfo($url);
        $this->file_name = md5(time().$original_name);
        $this->file_extension = strtolower($this->file_path_info['extension']);
        $this->full_name = $this->file_path.$this->file_name.'.'.$this->file_extension;
        file_put_contents($this->full_name, $raw_data);
        $this->table_name = $table_name;
        $this->item_id = $item_id;
        $this->file = $this->file_name.'.'.$this->file_extension;
        $this->original_name = $original_name;
        $this->extension = $this->file_extension;
        $this->creation_time = time();
        $this->main = $file_main_lable;
        $this->save();
        return $this;
    }

    /* Создание thumbnails */
    public function save_thumbnail($thumbnail) {
        $image = Image::factory($this->full_name);
        if (isset($thumbnail['crop'])) {
            $image->resize($thumbnail['w'], $thumbnail['h'], Image::INVERSE);
            $image->crop($thumbnail['w'], $thumbnail['h'], $thumbnail['crop']['x'], $thumbnail['crop']['y']);
        } else {
            if (isset($thumbnail['with_bg'])) {
                $image->thumbnail($thumbnail['w'], $thumbnail['h'], '#F1F1F1');
            } else {
                $w = ($image->width > $thumbnail['w']) ? $thumbnail['w'] : $image->width;
                $h = ($image->height > $thumbnail['h']) ? $thumbnail['h'] : $image->height;
                $image->resize($w, $h, NULL);
            }
            /*if ($image->width > $thumbnail['w'] || $image->height > $thumbnail['h']) {
                $image->resize($thumbnail['w'], $thumbnail['h'], NULL);
            }*/
        }
        $image->save($this->file_path.$thumbnail['w'].'x'.$thumbnail['h'].'_'.$this->file);
        return $this;
    }

    /* Удаление файла */
    public function delete_file($original_only = FALSE) {
        if ($original_only) {
            if (is_file($this->file_path.$this->file)) {
                unlink($this->file_path.$this->file);
            }            
        } else {
            $_unlink = function($full_name) {
                if (is_file($full_name)) {
                    unlink($full_name);
                }
            };
            $mask = glob($this->file_path.'*'.$this->file);
            if (is_array($mask) == FALSE) {
                $mask = array();
            }
            array_map($_unlink, $mask);
            $this->delete();
        }
        return $this;
    }

    /* Возвращает TRUE если файл является изображением */
    public function is_image($extension = '') {
        if (in_array($this->extension, $this->img_extensions) || in_array($extension, $this->img_extensions)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Повернуть изображение. */
    public function rotate_file($degrees) {
        $_rotate = function($full_name, $degrees) {
            if (is_file($full_name)) {
                $image = Image::factory($full_name);
                $image->rotate($degrees);
                $image->save();
            }
        };
        foreach (glob($this->file_path.'*'.$this->file) as $key => $filename) {
            $mask[] = $filename;
            $_degrees[] = $degrees;
        }
        array_map($_rotate, $mask, $_degrees);
    }

    /* Приведение массива $_FILES к удобному для обработки виду */
    public function array_correction(&$file) {
        $file_array = array();
        $file_count = count($file['name']);
        $file_keys = array_keys($file);
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $file[$key][$i];
            }
        }
        return $file_array;
    }

    /* Возвращает url адрес изображения */
    public function get_file_url($prefix = NULL, $dummy = TRUE) {
        $file_name = (empty($prefix)) ? $this->file : $prefix.'_'.$this->file;
        return ($this->loaded()) ? '/static/uploads/files/'.$file_name : (($dummy) ? '/static/images/no_photo.png' : '');
    }

}