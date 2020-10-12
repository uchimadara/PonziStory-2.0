<div class="col-lg-12">

    <h3>Pending Payment</h3>

    <h4>As payee</h4>

    <div id="paymentList" class="getList" data-url="<?= base_url() ?>admin/getList/pending_payments?payee_user_id=<?= $user->id ?>">
        <div class="loading"></div>
    </div>
    <h4>As payer</h4>

    <div id="paymentList" class="getList" data-url="<?= base_url() ?>admin/getList/pending_payments?payer_user_id=<?= $user->id ?>">
        <div class="loading"></div>
    </div>

</div>

<div class="col-lg-12">

    <h3>
        <?= $payments['received']->c ?> <?= pluralise('Payment', $payments['received']->c) ?>
        Received - <?= money($payments['received']->total) ?>
    </h3>

    <div id="paymentList" class="getList" data-url="<?= base_url() ?>admin/getList/payments_received?payee_user_id=<?= $user->id ?>">
        <div class="loading"></div>
    </div>


</div>
<div class="col-lg-12">
    <h3><?= $payments['sent']->c ?> <?= pluralise('Payment', $payments['sent']->c) ?> Sent - <?= money($payments['sent']->total) ?>
    </h3>

    <div id="paymentList" class="getList" data-url="<?= base_url() ?>admin/getList/payments_sent?payer_user_id=<?= $user->id ?>">
        <div class="loading"></div>
    </div>


</div>
<div class="clear"></div><? if ($ajax) { ?>
    <script>
        getList();
    </script>
<? } ?>
