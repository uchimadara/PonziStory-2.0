<div class="searchForm">
    <?= $list->search_form() ?>
</div>


<div id="<?= $list->listName() ?>" class="pageable getList" data-url="<?= $list->listURL() ?>">
    <span class="loading"></span>
</div>

<script type="text/javascript">
    $.get('<?=$list->listUrl()?>', function(d){
        $('#<?= $list->listName() ?>').html(d);
    })
</script>