<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'files' => array(
        'dir' => DOCROOT.'static'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR,
        'images' => array(
            'max_width' => 1920,
            'max_height' => 1080
        )
    ),
    'multilingual_models' => array(
        'Esup_Common_Text',
        'Esup_Common_File',
        'Esup_Common_Map',
        'Esup_Menu',
        'Esup_Page',
        'Esup_Article_Category',
        'Esup_Article',
        'Esup_Slider',
        'Esup_Social',
        'Esup_Gallery'
    ),
    'top_menu' => array(
        array(
            'text' => 'Наполнение',
            'access_level' => 0,
            'list' => array(
                array(
                    'type' => 'menu',
                    'text' => 'Меню сайта',
                    'link' => 'menu',
                    'access_level' => 0
                ),
                array(
                    'type' => 'menu',
                    'text' => 'Страницы',
                    'link' => 'pages',
                    'access_level' => 0
                ),
                array(
                    'type' => 'menu',
                    'text' => 'Статьи',
                    'link' => 'articles',
                    'access_level' => 0
                ),
                array(
                    'type' => 'menu',
                    'text' => 'Категории статей',
                    'link' => 'article_categories',
                    'access_level' => 10
                ),
                array(
                    'text' => 'Слайдер',
                    'link' => 'slider',
                    'access_level' => 0
                ),
                array(
                    'type' => 'menu',
                    'text' => 'Галереи',
                    'link' => 'galleries',
                    'access_level' => 10
                ),
                array(
                    'type' => 'menu',
                    'text' => 'Социальные сети',
                    'link' => 'social',
                    'access_level' => 0
                )
            )
        ),
        array(
            'text' => 'Настройки и прочее',
            'access_level' => 0,
            'list' => array(
                array(
                    'text' => 'Администраторы',
                    'link' => 'administrators',
                    'access_level' => 0
                ),
                array(
                    'text' => 'Файловый менеджер',
                    'link' => 'fmanager',
                    'access_level' => 0
                ),
                array(
                    'text' => 'Настройки',
                    'link' => 'settings',
                    'access_level' => 10
                ),
                array(
                    'text' => 'Текст на сайте',
                    'link' => 'text',
                    'access_level' => 10
                ),
                array(
                    'text' => 'Письма',
                    'link' => 'mailer',
                    'access_level' => 10
                ),
                array(
                    'text' => 'Метки на карте',
                    'link' => 'maps',
                    'access_level' => 0
                ),
                array(
                    'text' => 'Языки',
                    'link' => 'languages',
                    'access_level' => 10
                )
            )
        )
    )
);