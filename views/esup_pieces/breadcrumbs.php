<?php 
	$parent_field = $model->options['render']['tree_structure']['field'];
	$breadcrumbs = $model->breadcrumbs(Arr::get($_GET, $parent_field));
?>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/esup/<?php echo $model->options['render']['link'] ?>">Корневой каталог</a></li>
		<?php foreach ($breadcrumbs as $key => $item): ?>
			<?php if (Arr::get($_GET, $parent_field) == $item['id']): ?>
				<li class="breadcrumb-item active" aria-current="page"><?php echo $item['title'] ?></li>
			<?php else: ?>
				<li class="breadcrumb-item"><a href="/esup/<?php echo $model->options['render']['link'] ?>?<?php echo $parent_field ?>=<?php echo $item['id'] ?>"><?php echo $item['title'] ?></a></li>
			<?php endif ?>
		<?php endforeach ?>
	</ol>
</nav>