
<div class="form-group listSearch">
    <?= number_format($list->total()) ?> total
    <input type="text" class="form-control searchList main-search" url="<?= $list->listURL() ?>" data-div="<?= $list->listName() ?>"/>
</div>

<div id="<?= $list->listName() ?>" class="getList" data-url="<?= $list->listURL() ?>">
    <?=$list->render()?>
</div>
