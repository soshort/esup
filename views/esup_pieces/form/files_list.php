<?php if (count($files) > 0): ?>
	<?php
		/* Define image thumbnail path and prefix */
		if (empty($group['esup_thumbnail']))
		{
			$thumbnail = '/static/uploads/files/';
		}
		else
		{
			$thumbnail = '/static/uploads/files/'.$group['esup_thumbnail'].'_';
		}
	?>
	<div class="file edit_cont">
	    <div class="row flex_container">
			<?php foreach ($files as $key => $item): ?>
				<div class="flex_item main_cont">
					<?php if ($item->is_image()): ?>
			            <div class="pic_cont">
			            	<div class="file_name"><?php echo Text::limit_chars($item->original_name, 20) ?></div>
			                <a href="/static/uploads/files/<?php echo $item->file.'?ts='.$item->creation_time ?>" data-fancybox="<?php echo $group_name ?>">
			                	<img src="<?php echo $thumbnail.$item->file.'?ts='.$item->creation_time ?>" class="rounded">
			                </a>
			                <input class="crop-input" type="hidden" name="crop[<?php echo $group_name ?>][<?php echo $item->id ?>]" value="<?php echo HTML::entities($item->crop_data) ?>" data-ar-width="<?php echo Arr::get($group, 'ar_width', 4) ?>" data-ar-height="<?php echo Arr::get($group, 'ar_height', 3) ?>">
			            </div>
			        <?php else: ?>
			            <div class="pic_cont">
			            	<div class="file_name"><?php echo Text::limit_chars($item->original_name, 20) ?></div>
			                <a href="<?php echo $fullsize.$item->file.'?ts='.$item->creation_time ?>">
			                	<span class="octicon octicon-file"></span>
			                </a>
			            </div>
			        <?php endif ?>
		            <div class="icons_cont">
		                <div class="item_cont">
		                    <input type="radio" name="file_main_<?php echo $item->group_name ?>" <?php echo ($item->main == 1) ? 'checked="checked"' : '' ?> data-file-id="<?php echo $item->id ?>" data-item-id="<?php echo $item->item_id ?>" data-item-group-name="<?php echo $item->group_name ?>">
		                </div>
		                <div class="item_cont file-sort">
		                    <input type="text" class="form-control form-control-sm" placeholder="Сортировка" value="<?php echo $item->sort ?>" name="sort" data-sort-table="files" data-sort-field="sort" data-item-id="<?php echo $item->id ?>">
		                </div>
		                <div class="item_cont">
		                    <a href="/esup/files/edit/<?php echo $item->id ?>"><span class="octicon octicon-pencil"></span></a>
		                </div>
		                <div class="item_cont">
		                    <a href="/esup/files/delete/<?php echo $item->id ?>" class="file-delete red-link"><span class="octicon octicon-trashcan"></span></a>
		                </div>
		                <?php if ($item->is_image()): ?>
			                <div class="item_cont">
			                	<a href="/esup/files/rotate/<?php echo $item->id ?>" class="rotate rotate_left" data-degrees="-90" title="Повернуть влево"><span class="octicon octicon-arrow-left"></span></a>
			                </div>
			                <div class="item_cont">
			                	<a href="/esup/files/rotate/<?php echo $item->id ?>" class="rotate rotate_right" data-degrees="90" title="Повернуть вправо"><span class="octicon octicon-arrow-right"></span></a>
			                </div>
			            <?php endif ?>
		            </div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
<?php endif ?>