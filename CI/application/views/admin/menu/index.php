<h1 class="yellow">Menu</h1>

<div class="tab-container tile">
    <ul class="nav tab nav-tabs">
        <? foreach ($tabs as $id => $name) { ?>                
        <? if ($id == 0) { ?>
        <li class="active"><a href="#tab0"><?= ucwords($name) ?></a></li>

        <? } else { ?>
        <li><a href="#tab<?= $id ?>"><?= ucwords($name) ?></a></li>

        <? } ?>

        <?  } ?>
    </ul>
    <div class="tile-config dropdown">
        <a class="tile-menu" href="" data-toggle="dropdown"></a>
        <ul class="dropdown-menu pull-right text-right">
            <li><a href="<?= site_url('adminpanel/admin_menu/add') ?>">New menu item</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <p>Drag & drop to change menu item position</p>
        <? $i = 0; ?>
        <? foreach ($tabs as $t => $tname) { ?>
        <div class="tab-pane <?= ($i == 0 ? 'active' : '') ?>" id="tab<?= $i ?>">
            <div class="col-md-13">
                <div class="tile p-10">

                    <table class="table nomargin">
                        <thead>
                            <tr>
                                <th width="40%">Name</th>
                                <th width="30%">URL</th>
                                <th width="15%">Icon</th>
                                <th width="15%"></th>
                            </tr>
                        </thead>
                    </table>
                    <ul id="menu_sorting" place="<?= $tname ?>">
                        <? foreach ($menus[$i] as $m): ?>
                        <li id="item-<?= $m->id ?>">
                            <table style="width: 100%" class="table nomargin">
                                <tr>
                                    <td width="40%"><?= ($m->parent_id != 0 ? '|---- ' : '') ?><?= $m->name ?></td>
                                    <td width="30%"><?= $m->url ?></td>
                                    <td width="15%"><a class="<?= str_replace('side', 'menu', $m->icon) ?>" href="#" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                                    <td width="15%">
                                        <a class="button_blue borderWhite" href="<?= site_url('adminpanel/' . $this->uri->segment(2) . '/update/' . $m->id) ?>" />edit</a>
                                        <? if(count($m->children)==0){?> | <a class="confirm_row" href="<?= site_url('adminpanel/' . $this->uri->segment(2) . '/delete/' . $m->id) ?>" />delete</a><?}?>
                                    </td>
                                </tr>
                                <? if(count($m->children)>0){?>
                                <tr>
                                    <td colspan="4" class="nopadding">
                                        <table style="width: 100%" id="submenu_sorting">
                                            <tbody parent_id="<?= $m->id ?>">
                                                <? foreach ($m->children as $mch): ?>
                                                <tr id="item-<?= $mch->id ?>">
                                                    <td width="40%"><?= ($mch->parent_id != 0 ? '|---- ' : '') ?><?= $mch->name ?></td>
                                                    <td width="30%"><?= $mch->url ?></td>
                                                    <td width="15%"><a class="<?= str_replace('side', 'menu', $mch->icon) ?>" href="#" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
                                                    <td width="15%">
                                                        <a class="button_blue borderWhite" href="<?= site_url('adminpanel/' . $this->uri->segment(2) . '/update/' . $mch->id) ?>" />edit</a> |
                                                        <a class="confirm_row" href="<?= site_url('adminpanel/' . $this->uri->segment(2) . '/delete/' . $mch->id) ?>" />delete</a>
                                                    </td>
                                                </tr>

                                                <? endforeach; ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <?}?>
                            </table>
                        </li>
                        <? endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <? $i++; }?>

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