<div class="col-md-12">
    <div class="tile">
        <h2 class="tile-title">User profile</h2>

        <div class="getForm" data-url="<?= $formURL ?>">
            <div class="loading"></div>
        </div>
    </div>
</div>
<? if ($ajax) { ?>
<script>
    $.get('<?= $formURL ?>', function (data) {
            $('.getForm').html(data);
        });
</script>
<? } ?>
<div class="clear"></div>