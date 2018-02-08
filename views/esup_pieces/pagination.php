<?php
	$options = array(
		'view' => 'esup_pieces/pagination/floating',
		'total_items' => $total_items,
		'items_per_page' => $items_per_page
	);
	$route_options = array(
		'controller' => mb_strtolower(Request::$initial->controller())
	);
	echo Pagination::factory($options)
		->route_params($route_options);
?>