<input type="text" name="<?php echo $filter_key ?>" class="form-control filter-text" placeholder="<?php echo $filter['render']['title'] ?>" value="<?php echo Arr::get($_GET, $filter_key) ?>">