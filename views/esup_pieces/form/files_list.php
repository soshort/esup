<?php
	$thumbnail = (empty($field['esup_thumbnail'])) ? '/static/uploads/files/' : '/static/uploads/files/'.$field['esup_thumbnail'].'_';
	$fullsize = (empty($field['esup_fullsize'])) ? '/static/uploads/files/' : '/static/uploads/files/'.$field['esup_fullsize'].'_';
?>
<div class="file edit_cont">
    <div class="row flex_container">
		<?php foreach ($files as $key => $item): ?>
			<div class="flex_item main_cont">
				<?php if ($item->is_image()): ?>
		            <div class="pic_cont">
		                <a href="<?php echo $fullsize.$item->file ?>" target="_blank">
		                	<img src="<?php echo $thumbnail.$item->file ?>" class="img-rounded">
		                </a>
		            </div>
		        <?php else: ?>
		            <div class="pic_cont">
		            	<div class="file_name"><?php echo Text::limit_chars($item->original_name, 20) ?></div>
		                <a href="<?php echo $fullsize.$item->file ?>" target="_blank">
		                	<span class="glyphicon glyphicon-open-file"></span>
		                </a>
		            </div>
		        <?php endif ?>
	            <div class="icons_cont">
	                <div class="item_cont">
	                    <input type="radio" name="file_main_<?php echo $item->table_name ?>" <?php echo ($item->main == 1) ? 'checked="checked"' : '' ?> data-file-id="<?php echo $item->id ?>" data-item-id="<?php echo $item->item_id ?>" data-item-table="<?php echo $item->table_name ?>">
	                </div>
	                <div class="item_cont file-sort">
	                    <input type="text" class="form-control input-xs" placeholder="Сортировка" value="<?php echo $item->sort ?>" name="sort" data-sort-table="files" data-sort-field="sort" data-item-id="<?php echo $item->id ?>">
	                </div>
	                <div class="item_cont">
	                    <a target="_blank" href="/esup/files/edit/<?php echo $item->id ?>"><span class="glyphicon glyphicon-pencil"></span></a>
	                </div>
	                <div class="item_cont">
	                    <a href="/esup/files/delete/<?php echo $item->id ?>" class="file-delete red-link"><span class="glyphicon glyphicon-trash"></span></a>
	                </div>
	                <?php if ($item->is_image()): ?>
		                <div class="item_cont">
		                	<a href="/esup/files/rotate/<?php echo $item->id ?>" class="rotate rotate_left" data-degrees="-90"><span class="glyphicon glyphicon-repeat"></span></a>
		                </div>
		                <div class="item_cont">
		                	<a href="/esup/files/rotate/<?php echo $item->id ?>" class="rotate rotate_right" data-degrees="90"><span class="glyphicon glyphicon-repeat"></span></a>
		                </div>
		            <?php endif ?>
	            </div>
			</div>
		<?php endforeach ?>
	</div>
</div>