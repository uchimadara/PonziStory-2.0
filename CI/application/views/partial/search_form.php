<? if ($form) { ?>

<div class="formContainer">
    <form id="<?= $name ?>-search" action="<?=$url?>" name="<?= $name ?>_search" method="post" enctype="multipart/form-data" class="frmSearch">

        <?php foreach ($form as $field) {

            if (array_key_exists('label', $field)) {
                echo '<label style="float:left; width:'.$field['label_width'].';text-align:right;margin-right:4px;font-size:1em;">'.$field['label'], ':</label>';
            }
            $width = (array_key_exists('width', $field)) ? 'width:'.$field['width'].';' : '280px';
            switch ($field['type']) {
                case 'break':
                    echo '<div style="clear:both;';
                    if (array_key_exists('height', $field)) echo "height:{$field['height']};";
                    echo '"></div>';
                    break;

                case 'text':

                    echo form_input($field['name'], '', 'class="form-control input-md"  style="float:left;'.$width.'"');
                    break;

                case 'select':

                    $values = array(''=>'') + $this->picklist->select_values($field['select_list'], TRUE);
                    echo form_dropdown($field['name'], $values, '', ' style="float:left;'.$width.'" class="form-control"');
                    break;
            }

         } ?>

        <div class="clear"></div>
        <div class="formBottom m-t-5">
            <input type="submit" value="Search" class="btn btn-alt btn-xs"/>
        </div>
    </form>

</div>

<? } else { ?>
    <div class="form-group listSearch">

        <input type="text" class="form-control main-search searchList" url="<?= $url ?>" data-div="<?= $name ?>"/>
    </div>

<? } ?>
