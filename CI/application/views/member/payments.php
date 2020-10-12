<h1>Donations</h1>

<div class="col-lg-12">
    <div class="tile">

        <h2 class="tile-title"><?=$payments['received']->c?> <?= pluralise('Donation', $payments['received']->c) ?> Received - <?=money($payments['received']->total)?></h2>
        <div id="paymentList" class="getList" data-url="<?= base_url() ?>member/getList/payments_received?payee_user_id=<?=$userData->id?>">
            <div class="loading"></div>
        </div>

    </div>

</div>
<div class="col-lg-12">
    <div class="tile">

        <h2 class="tile-title"><?= $payments['sent']->c ?> <?=pluralise('Donation', $payments['sent']->c)?> Sent - <?= money($payments['sent']->total) ?></h2>

        <div id="paymentList" class="getList" data-url="<?= base_url() ?>member/getList/payments_sent?payer_user_id=<?= $userData->id ?>">
            <div class="loading"></div>
        </div>

    </div>

</div>
