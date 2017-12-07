<?php
	if (isset($field['default']) && Arr::get($_GET, $field['default'], FALSE)) {
		if (Request::current()->action() == 'add') {
			$selected = Request::$initial->query($field['default']);
		} else {
			$selected = $model->$key;
		}
	} else {
		$selected = $model->$key;
	}
?>
<?php if (isset($field['recursive']) && $field['recursive'] == TRUE): ?>
	<div class="form-group">
		<label for="form_<?php echo $key ?>" class="col-sm-2 control-label"><?php echo $field['label'] ?></label>
		<div class="col-sm-10">
			<?php echo View::factory('esup_pieces/form/_select_recursive', array(
				'name' => $key,
				'model' => $field['relation']['model'],
				'selected' => $selected,
				'default_value' => (isset($field['show_default_value']) && $field['show_default_value'] == TRUE) ? TRUE : FALSE,
				'attr' => array('id' => 'form_'.$key, 'class' => 'form-control'),
			)) ?>
		</div>
	</div>
<?php else: ?>
	<div class="form-group">
		<label for="form_<?php echo $key ?>" class="col-sm-2 control-label"><?php echo $field['label'] ?></label>
		<div class="col-sm-10">
			<?php echo Form::select($key, ORM::factory($field['relation']['model'])->get_array_for_select((isset($field['show_default_value'])) ? TRUE : FALSE, $field['relation']['id_field'], $field['relation']['title_field']), $selected, array('id' => 'form_'.$key, 'class' => 'form-control')) ?>
		</div>
	</div>
<?php endif ?>