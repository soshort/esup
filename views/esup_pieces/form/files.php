<div class="form-group row">
	<label for="form_<?php echo $group_name ?>" class="col-sm-2 col-form-label"><?php echo $group['label'] ?></label>
	<div class="col-sm-10">
		<input type="file" class="form-control" id="form_<?php echo $group_name ?>" placeholder="<?php echo $group['label'] ?>" name="<?php echo (isset($group['multiple'])) ? $group_name.'[]' : $group_name ?>" <?php echo (isset($group['multiple'])) ? 'multiple="multiple"' : '' ?>>
		<?php echo View::factory('esup_pieces/form/files_list', array(
			'files' => $model->files->where('group_name', '=', $group_name)->get_items(),
			'group' => $group,
			'group_name' => $group_name
		)) ?>
	</div>
</div>