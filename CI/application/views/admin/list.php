<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title"><?= $page_title ?> [<?= $list->total() ?> total]</h2>

        <? if (isset($buttons) && !empty($buttons)) { ?>
            <div class="tile-config dropdown">
                <a data-toggle="dropdown" href="" class="tile-menu"></a>
                <ul class="dropdown-menu pull-right text-right">
                    <? foreach ($buttons as $b) { ?>
                        <li><?= anchor(SITE_ADDRESS.$b['uri'], $b['title'], $b['extra']) ?></li>
                    <? } ?>
                </ul>
            </div>

        <? } ?>

        <div class="form-group listSearch">
            <label for="search">Search</label>
            <input type="text" class="form-control searchList" url="<?= $list->listURL() ?>" data-div="<?= $list->listName() ?>"/>
        </div>

        <div id="<?= $list->listName() ?>" class="pageable rms-sortable getList" data-url="<?= $list->listURL() ?>">
            <span class="loading"></span>

        </div>
       <? if ($list->listName() == 'rejected_payments') {?>

           <?= $list->listURL() ?>

        <?php } ?>
    </div>
</div>
