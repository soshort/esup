<?php defined('SYSPATH') or die('No direct script access.');

Route::set('esup common modules', 'esup(/<controller>(/<action>(/<id>)))', array('controller' => 'esup|administrators|settings|files|mailer|fmanager|languages|text|sorting|maps|auth'))
	->defaults(
		array(
			'controller' => 'Esup',
			'action' => 'index',
			'directory' => 'Esup_Common'
		)
	);

Route::set('esup other', 'esup/<controller>(/<action>(/<id>))', array('controller' => '\w+', 'id' => '\w+'))
	->defaults(
		array(
			'controller' => 'Esup',
			'action' => 'index',
			'directory' => 'Esup'
		)
	);