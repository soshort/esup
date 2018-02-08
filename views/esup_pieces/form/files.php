<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<!-- <div class="input-group">
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo (isset($field['multiple'])) ? $key.'[]' : $key ?>" <?php echo (isset($field['multiple'])) ? 'multiple="multiple"' : '' ?>>
				<label class="custom-file-label" for="form_<?php echo $key ?>">Выбрать файл</label>
			</div>
		</div> -->
		<input type="file" class="form-control" id="form_<?php echo $key ?>" placeholder="<?php echo $field['label'] ?>" name="<?php echo (isset($field['multiple'])) ? $key.'[]' : $key ?>" <?php echo (isset($field['multiple'])) ? 'multiple="multiple"' : '' ?>>
		<?php echo View::factory('esup_pieces/form/files_list', array(
			'files' => $model->files->where('table_name', '=', $key)->get_items(),
			'field' => $field
		)) ?>
	</div>
</div>