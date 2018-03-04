<div class="form-group row">
	<label for="form_<?php echo $key ?>" class="col-sm-2 col-form-label"><?php echo $field['label'] ?></label>
	<div class="col-sm-10">
		<?php $related_model = ORM::factory($field['relation']['model'], Arr::get($_GET, $field['default'])) ?>
		<?php if ($related_model->loaded()): ?>
			<div style="padding-top: 7px">
				<a href="/esup/<?php echo $related_model->options['render']['link'] ?>/view/<?php echo $related_model->{$field['relation']['id_field']} ?>"><?php echo $related_model->{$field['relation']['title_field']} ?></a>
				<input type="hidden" name="<?php echo $key ?>" id="form_<?php echo $key ?>" value="<?php echo $related_model->{$field['relation']['id_field']} ?>">
			</div>
		<?php else: ?>
			<div style="padding-top: 7px">Не указан</div>
		<?php endif ?>
	</div>
</div>