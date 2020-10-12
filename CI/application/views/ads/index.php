<? if ($userData->account_level == 0) { ?>
    <div class="col-lg-12">

        <div class="alert alert-warning">
            You must <a href="/back_office/upgrade">upgrade your account</a> to create text ads.
        </div>
    </div>
<? } else { ?>
<div class="col-lg-12">
    <h1>Text Ads
        <? if ($maxAds > $textAdCount) { ?>
            &nbsp; &nbsp;
            <a class="popup btn btn-alt" title="New Text Ad" href="<?= site_url('member/form/text_ad') ?>">
                New Text Ad
            </a>
        <? } ?>
    </h1>

    <h2>You have
        <span id="textAdCreditTotal"><?= number_format($adCredits) ?></span> unassigned ad credits.
    </h2>
</div>
    <div class="col-lg-12">
        <div class="tile">
            <p>
                Number of ads you may have is determined by your account level.<br/>
                Your stage <b><?= $userData->account_level ?></b> upgrade allows you to have up to <?= $maxAds ?> ads.
            </p>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="tile">
            <h2 class="tile-title">Text Ads [<?= $textAdCount ?> of <?= $maxAds ?>]</h2>

            <div class="removable">

                <div class="formContainer">
                    <?= $textAds ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>

