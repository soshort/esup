<?php if (empty($lvl)): ?>
    <?php
        $lvl = 0;
        $a_string = '';
        if (isset($attr)) {
            foreach ($attr as $key => $a) {
                $a_string .= $key.'="'.$a.'" ';
            }
        }
    ?>
    <select name="<?php echo $name ?>" <?php echo $a_string ?>>
<?php endif ?>
<?php if (empty($parent_id)): ?>
    <?php $parent_id = NULL ?>
<?php endif ?>
<?php if (isset($default_value) && $default_value == TRUE): ?>
    <option value="" <?php echo (empty($selected)) ? 'selected="selected"' : '' ?>>Нет</option>
<?php endif ?>
<?php foreach (ORM::factory($model)->where('parent_id', '=', $parent_id)->order_by('sort', 'ASC')->find_all() as $key => $item): ?>
    <?php $s = ($selected == $item->id) ? 'selected="selected"' : ''; ?>
    <option value="<?php echo $item->id ?>" <?php echo $s ?>><?php echo str_repeat('- ', $lvl).$item->title ?></option>
    <?php echo View::factory('esup_pieces/form/_select_recursive', array(
        'model' => $model,
        'selected' => $selected,
        'parent_id' => $item->id,
        'lvl' => $lvl + 1
    )) ?>
<?php endforeach ?>
<?php if (empty($lvl)): ?>
    </select>
<?php endif ?>