<h1>Accounts</h1>
<div id="accountWidget" class="tile m-l-20">

    <? $used = array();

    if ($balances):  ?>
        <div class="row">
            <h3 class="m-t-20">Your Accounts</h3>
            <? foreach ($balances as $balance) { $used[] = $balance->code; ?>

                <div class="depositMethodCashier">
                    <div class="logoDepositmethodsCashier m-b-10">
                        <img src="<?=asset('images/currencies/logo-'.$balance->code.'.png')?>" />
                    </div>
                    <div class="alert alert-info alert-narrow <?= $balance->code ?>"><?=$balance->account?></div>
                    <!--
                    <a class="btn btn-sm popup" title="" href="<?=site_url('member/show_payments/'.$balance->code)?>">View Payments</a>
                    -->
                    <a class="btn btn-sm replace" data-div="page-content" title="<?= $balance->name ?>" href="<?= site_url('member/method/'.$balance->code) ?>">Change Account</a>
                </div>
            <? } ?>
        </div>
    <? endif; ?>

    <? if (count($used) < count($paymentMethods)) { ?>

        <div class="row">

            <h3 class="m-t-20">Add new account</h3>
            <?
            foreach ($paymentMethods as $method):
                if (!in_array($method->code, $used)):
                    ?>
                <div class="depositMethodCashier" id="method-<?= $method->code?>">
                    <a class="logoDepositmethodsCashier replace" data-div="page-content" title="<?= $method->name ?>" href="<?= site_url('member/method/'.$method->code) ?>">
                        <img src="<?= asset('images/currencies/logo-'.$method->code.'.png') ?>"/>
                    </a>
                </div>
                <?
                endif;
            endforeach;
            ?>
        </div>

    <? } ?>
</div>
