<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
    'single' => array(
        'collections' => array(
            'jquery-cdn' => array(
                '//code.jquery.com/jquery-3.2.1.min.js'
            ),
            'jquery-ui-cdn' => array(
                'jquery-cdn',
                '//code.jquery.com/ui/1.12.1/themes/overcast/jquery-ui.css',
                '//code.jquery.com/ui/1.12.1/jquery-ui.min.js',
                '//ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/ui.datepicker-ru.js'
            ),
            'popper-cdn' => array(
                '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'
            ),
            'bootstrap-cdn' => array(
                'jquery-cdn',
                'popper-cdn',
                '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
                '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'
            ),
            'octicons-cdn' => array(
                '//cdnjs.cloudflare.com/ajax/libs/octicons/4.4.0/font/octicons.min.css'
            ),
            'animate-cdn' => array(
                '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'
            ),
            'noty-cdn' => array(
                'jquery-cdn',
                '//cdnjs.cloudflare.com/ajax/libs/jquery-noty/2.4.1/packaged/jquery.noty.packaged.min.js'
            ),
            'select2-cdn' => array(
                'jquery-cdn',
                '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css',
                '//cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css',
                '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js',
                '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/i18n/ru.js'
            ),
            'fancybox-cdn' => array(
                'jquery-cdn',
                '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css',
                '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js'
            ),
            'cropper-cdn' => array(
                '//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.3.2/cropper.min.css',
                '//cdnjs.cloudflare.com/ajax/libs/cropperjs/1.3.2/cropper.min.js'
            ),
            'ckeditor-cdn' => array(
                '//cdn.ckeditor.com/4.5.11/full/ckeditor.js'
            ),
            'yandex-maps-cdn' => array(
                '//api-maps.yandex.ru/2.0/?load=package.full&amp;lang=ru-RU'
            ),
            'ace-editor-cdn' => array(
                '//cdnjs.cloudflare.com/ajax/libs/ace/1.3.1/ace.js'
            )
        ),
        'autoload' => array(
            'jquery-cdn',
            'popper-cdn',
            'bootstrap-cdn',
            'octicons-cdn',
            'animate-cdn',
            'noty-cdn',
            'select2-cdn'
        ),
        'js_regex' => '/(?:.\.js|yandex\.ru\/)/i'
    ),
    'pipeline' => array(
        'collections' => array(
            'common-css' => array(
                'common.css'
            ),
            'common-js' => array(
                'common.js'
            ),
            'form-js' => array(
                'form.js'
            ),
            'settings-scripts-js' => array(
                'settings/scripts.js'
            ),
            'settings-sitemap-js' => array(
                'settings/sitemap.js'
            ),
            'languages-index-js' => array(
                'languages/index.js'
            )
        ),
        'autoload' => array(
            'common-css',
            'common-js'
        ),
        'public_dir' => DOCROOT,
        'js_dir' => '/modules/esup/static/js',
        'css_dir' => '/modules/esup/static/css',
        'pipeline' => 'v0.4',
        'pipeline_dir' => 'assets'
    )
);