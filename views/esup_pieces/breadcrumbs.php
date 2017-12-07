<?php 
	$parent_field = $model->options['render']['tree_structure']['field'];
	$breadcrumbs = $model->breadcrumbs(Arr::get($_GET, $parent_field))
?>
<ol class="breadcrumb">
	<li><a href="/esup/<?php echo $model->options['render']['link'] ?>">Корневой каталог</a></li>
	<?php foreach ($breadcrumbs as $key => $item): ?>
		<?php if (Arr::get($_GET, $parent_field) == $item['id']): ?>
			<li class="active"><?php echo $item['title'] ?></li>
		<?php else: ?>
			<li><a href="/esup/<?php echo $model->options['render']['link'] ?>?<?php echo $parent_field ?>=<?php echo $item['id'] ?>"><?php echo $item['title'] ?></a></li>
		<?php endif ?>
	<?php endforeach ?>
</ol>