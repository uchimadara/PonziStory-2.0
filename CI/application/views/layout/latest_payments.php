<div class="lp-div str_wrap">
    <? foreach ($payments as $p) { ?>
        <span class="single">
            <img src="<?= avatar($p->avatar) ?>" class="payment-avatar" alt=""><?= $p->username ?> - <?= date('d-M h:i', $p->approved) ?>
            <span><?= money($p->amount) ?></span>
        </span>
    <? } ?>

</div>

