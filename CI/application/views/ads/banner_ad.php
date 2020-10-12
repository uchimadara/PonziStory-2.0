<p>
    Click the ad name to view detailed statistics.
    Enter a number in the "Assign Credits" column and click the button to assign credits to an existing ad.
    Deleting an ad will return unused credits to your balance.
    <br/><i><b>Note</b>: Unused credits is the difference between CREDITS and SHOWN.</i>
</p>

<form action="<?= base_url() ?>adverts/assign_credits" class="frm_ajax" method="post" name="acForm">
    <input type="hidden" name="table" value="text_ad"/>

    <div id="textAdList" class="pageable sortable getList" data-url="<?= base_url() ?>member/getList/user_banner_ads/?user_id=<?= $userData->id ?>">
       <div class="loading"></div>
    </div>
    <?php if ($userData->text_ad_credits > 0) { ?>
        <div class="formBottom">
            <input class="btn btn-alt" type="submit" value="Assign Credits"/>
        </div>
    <?php } ?>
</form>
<?php if ($ajax) { ?>
    <script>
        $.get('<?= base_url() ?>member/getList/user_banner_ads/?user_id=<?= $userData->id ?>', function (data) {
            $('#textAdList').html(data);
        });
    </script>
<?php } ?>
