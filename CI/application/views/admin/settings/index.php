<h1 class="yellow">Settings</h1>

<div class="tab-container tile">
    <ul class="nav tab nav-tabs">
        <? foreach ($tabs as $id => $name) { ?>
            <? if ($id == 0) { ?>
                <li class="active"><a href="#tab0"><?= ucwords($name) ?></a></li>

            <? } else { ?>
                <li><a href="#tab<?= $id ?>"><?= ucwords($name) ?></a></li>

            <? } ?>

        <? } ?>
    </ul>

    <div class="tab-content">
        <? $i = 0; ?>
        <? foreach ($settings as $s) { ?>
            <div class="tab-pane <?= ($i == 0 ? 'active' : '') ?>" id="tab<?= $i ?>">
                <div class="col-md-12">
                    <div class="tile">

                        <table class="table">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th></th>
                            </tr>
                            <? foreach ($settings[$i] as $s): ?>
                                <tr>
                                    <div class="formContainer">
                                        <?= form_open(site_url('adminpanel/'.$this->uri->segment(2).'/update/'.$s->id), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'SettingsAddFrm')); ?>
                                        <td><?= $s->label ?><input type="hidden" name="name" value="<?= $s->name ?>"/>
                                        </td>
                                        <td><?= $s->description ?></td>
                                        <td>
                                            <? switch ($s->format) {
                                                case 'int':
                                                    ?>

                                                    <input class="form-control floatLeft" style="display:inline; width:75%" onkeyup="javascript:maskInteger(this);" type="text" name="value" id="value" value="<?= $s->value ?>"/>

                                                    <? break;
                                                case 'text':
                                                    ?>

                                                    <input class="form-control floatLeft" style="display:inline; width:75%" type="text" name="value" id="value" value="<?= $s->value ?>"/>

                                                    <? break;
                                                case 'float':
                                                    ?>

                                                    <input class="form-control floatLeft" style="display:inline; width:75%" onkeyup="javascript:maskAmount(this);" type="text" name="value" id="value" value="<?= $s->value ?>"/>

                                                    <? break;
                                                case 'date':
                                                    ?>

                                                    <input class="form-control dp floatLeft" style="display:inline; width:75%" type="text" name="value" id="value" value="<?= date('Y-m-d', $s->value) ?>"/>

                                                    <? break;
                                                case 'datetime':
                                                    ?>
                                                    <input size="16" type="text" name="value" id="value" value="<?= date('Y-m-d h:i', $s->value) ?>" readonly class="form-control form_datetime floatLeft" style="display:inline; width:75%">
                                                    <!--
                                                    <input class="form-control dp datetime-pick" type="text" name="value" id="value" value="<?= $s->value ?>"/>
                                                    -->

                                                    <? break;
                                                case 'yes_no_int':
                                                    ?>

                                                    <input type="checkbox" name="value" id="value" value="1" <?= ($s->value == '1') ? 'checked="checked"' : '' ?> />
                                                    <!--
                                                    <input class="form-control dp datetime-pick" type="text" name="value" id="value" value="<?= $s->value ?>"/>
                                                    -->

                                                    <? break; ?>
                                                <?
                                            } ?>

                                        </td>
                                        <td><input class="btn btn-alt btn-sm" type="submit" value="save"/></td>
                                        <?= form_close() ?>
                                    </div>
                                </tr>
                            <? endforeach; ?>
                        </table>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <? $i++;
        } ?>

    </div>
</div>
<? if ($ajax) { ?>
    <script>
        $('.tab').tabs();
        $('.tab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

    </script>
<? } ?>