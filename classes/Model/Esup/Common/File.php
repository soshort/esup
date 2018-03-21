<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Esup_Common_File extends Model_Esup {

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

    protected $_table_name = 'files';

    private $config = array();
    private $source = '';
    private $source_name = '';
    private $source_extension = '';
    private $source_path_info = array();
    private $img_extensions = array('jpg', 'png', 'jpeg', 'gif', 'bmp');

    public function __construct($id = NULL)
    {
        parent::__construct($id);
        $this->config = Kohana::$config->load('esup.files');
    }

    /* Sets the working directory */
    public function set_dir($dir)
    {
        $this->config['dir'] = $dir;
        return $this;
    }

    /* Uploads the file and link it to the model */
    public function save_file($file, $group_name, $item_id, $main_file)
    {
        if ( ! $this->set_source($file))
            return FALSE;
        /* If the file is an image, then resize it */
        if ($this->is_image($this->source_extension))
        {
            $image = Image::factory($this->source);
            if ($image->width > $this->config['images']['max_width'] OR $image->height > $this->config['images']['max_height'])
            {
                $image->resize($this->config['images']['max_width'], $this->config['images']['max_height']);
            }
            $image->save($this->source, 90);
        }
        $this->group_name = $group_name;
        $this->item_id = $item_id;
        $this->file = $this->source_name.'.'.$this->source_extension;
        $this->original_name = $this->source_path_info['filename'];
        $this->extension = $this->source_extension;
        $this->creation_time = time();
        $this->main = $main_file;
        $this->save();
        return $this;
    }

    /* Creates image thumbnails */
    public function thumbnail($thumbnail)
    {
        $image = Image::factory($this->source);
        if ($bg = Arr::get($thumbnail, 'background'))
        {
            $image->thumbnail($thumbnail['w'], $thumbnail['h'], $bg['color']);
        }
        else
        {
            $w = ($image->width > $thumbnail['w']) ? $thumbnail['w'] : $image->width;
            $h = ($image->height > $thumbnail['h']) ? $thumbnail['h'] : $image->height;
            $image->resize($w, $h);
        }
        $image->save($this->config['dir'].$thumbnail['w'].'x'.$thumbnail['h'].'_'.$this->file);
        return $this;
    }

    /* Unlinks the file */
    public function delete_file()
    {
        $_unlink = function($full_name)
        {
            if (is_file($full_name))
            {
                unlink($full_name);
            }
        };
        $mask = glob($this->config['dir'].'*'.$this->file);
        if ( ! is_array($mask))
        {
            $mask = array();
        }
        array_map($_unlink, $mask);
        $this->delete();
        return $this;
    }

    /* Crops the image and its thumbnails */
    public function crop_image($options, $json_string)
    {
        $crop_data = json_decode($json_string);
        $source_path = $this->config['dir'].$this->file;
        foreach (Arr::get($options, 'thumbnails', array()) as $thumb_name => $thumb_arr)
        {
            $image = Image::factory($source_path)
                ->crop($crop_data->width, $crop_data->height, $crop_data->x, $crop_data->y)
                ->resize($thumb_arr['w'], $thumb_arr['h'])
                ->save($this->config['dir'].$thumb_name.'_'.$this->file);
        }
        $this->crop_data = $json_string;
        $this->creation_time = time();
        $this->save();
        return $this;
    }

    /* Rotates the image */
    public function rotate_image($degrees)
    {
        $_rotate = function($full_name, $degrees)
        {
            if (is_file($full_name))
            {
                $image = Image::factory($full_name)
                    ->rotate($degrees)
                    ->save();
            }
        };
        foreach (glob($this->config['dir'].'*'.$this->file) as $key => $filename)
        {
            $mask[] = $filename;
            $_degrees[] = $degrees;
        }
        array_map($_rotate, $mask, $_degrees);
        $this->creation_time = time();
        $this->save();
        return $this;
    }

    /* Returns TRUE if the file is image */
    public function is_image($extension = '')
    {
        if (in_array($this->extension, $this->img_extensions) OR in_array($extension, $this->img_extensions))
            return TRUE;
        else
            return FALSE;
    }

    /* Corrects multiple $_FILES array */
    public function array_correction(&$file)
    {
        $result = array();
        $count = count($file['name']);
        $keys = array_keys($file);
        for ($i = 0; $i < $count; $i++)
        {
            foreach ($keys as $key)
            {
                $result[$i][$key] = $file[$key][$i];
            }
        }
        return $result;
    }

    /* Returns file url */
    public function get_file_url($prefix = NULL, $dummy = TRUE)
    {
        $file_name = (empty($prefix)) ? $this->file : $prefix.'_'.$this->file;
        if ($this->loaded())
            return '/static/uploads/files/'.$file_name.'?ts='.$this->creation_time;
        else
            return $dummy ? '/static/images/no-image.svg' : '';
    }

    /* Sets a source of the file to work with */
    private function set_source($file)
    {
        $this->source_path_info = pathinfo($file['name']);
        $this->source_name = md5(md5_file($file['tmp_name']).time());
        $this->source_extension = strtolower($this->source_path_info['extension']);
        $this->source = Upload::save($file,
            $this->source_name.'.'.$this->source_extension,
            $this->config['dir']);
        return (empty($this->source)) ? FALSE : TRUE;
    }

}