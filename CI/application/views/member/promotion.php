<div class="col-lg-12">
    <h1>Marketing Materials</h1>

    <? if ($userData->account_level > 0) { ?>

    <div class="p-10">
        <h2>Your Referral Link</h2>

        <div class="memberRefLink">
            <?= $refUrl ?>
        </div>

        <div class="referralBanner">
            <? foreach ($banners as $img) { ?>
                <br/>Image Location: <?= htmlentities(SITE_ADDRESS."banners/$img") ?>
                <br/><img alt="<?= SITE_NAME ?>" src="<?= SITE_ADDRESS.'banners/'.$img ?>"/>
                Code:<br/>
                <div style="border:1px solid #333333; padding:5px;font-size:.9em;">
                    &lt;a href=&quot;<?= $refUrl ?>&quot;&gt;&lt;img src=&quot;<?= SITE_ADDRESS.'banners/'.$img ?>&quot; /&gt;&lt;/a&gt;
                </div>
                <hr class="whiter"/>

            <? } ?>
        </div>

    </div>

    <? } else { ?>

        <div class="alert alert-warning">This page can be viewed only by upgraded members.
            <a href="<?= SITE_ADDRESS ?>back_office/upgrade">Upgrade now.</a>
        </div>

    <? } ?>

</div>
