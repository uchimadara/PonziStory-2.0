<h1> Investment Pending Approval </h1>
<!--<h2>Confirmations:  --><?//= $pending->confirmations ?><!--</h2>-->

<?php foreach ($pending as $pend){ ?>

    <div class="col-lg-12"  style="border-bottom: #0b0b0b double thick;margin-bottom: 30px">




<div class="col-lg-11 col-md-12" >

    <div class="alert alert-success">
        <i class="fa fa-clock-o" aria-hidden="true"></i>
        Elapsed time: <?= displayCountDown(now() - $pend->created, TRUE, TRUE) ?>
    </div>
    <div class="alert alert-info">
        Wait for the other member to confirm your payment or you may call to inform him/her of it
        (So as to speed up your confirmation)
    </div>

    <h3>Donation Sent To:</h3>

    <div class="col-md-4 fs16">
        <? if ($pend->settings->show_avatar) { ?>
            <img src="<?= avatar($pend->avatar) ?>"/> <br/>
            <b><?= $pend->first_name ?> <?= $pend->last_name ?></b><br/>
            <?= $pend->username ?>
        <? } ?>
    </div>
    <div class="memberSponsor col-md-8">
        <table class="table">
            <? if ($payee->settings->show_email) { ?>
                <tr>
                    <td>
                        <a href="mailto:<?= $pend->email ?>"><img src="<?= asset('images/social/email.png') ?>" style="width: 36px;"/>
                        </a>
                    </td>
                    <td align="left"><a href="mailto:<?= $pend->email ?>"><?= $pend->email ?></a></td>
                </tr>
            <? } ?>
            <? if ($payee->settings->show_phone && !empty($pend->phone)) { ?>
                <tr>
                    <td>
                        <img src="<?= asset('images/social/phone.png') ?>" style="width: 36px;"/>
                    </td>
                    <td align="left"><?= $pend->phone ?></td>
                </tr>
            <? } ?>
            <? if (isset($payee->settings->skype_id) && $payee->settings->show_skype == 1) { ?>
                <tr>
                    <td><a href="skype:<?= $payee->settings->skype_id ?>">
                            <img src="<?= asset('images/social/skype.png') ?>" style="width: 36px;"/>
                        </a>
                    </td>
                    <td align="left">
                        <a href="skype:<?= $payee->settings->skype_id ?>">
                            <?= $payee->settings->skype_id ?></a>
                    </td>
                </tr>
            <? } ?>

            <? if (!empty($payee->socialList)) { ?>
                <? foreach ($payee->socialList as $social) { ?>
                    <tr>
                        <td>
                            <a href="<?= $social->link ?>" target="_blank">
                                <img src="<?= asset("images/social/".strtolower(str_replace('+', '-plus', $social->name)).".png") ?>" width="30px"/>
                            </a>
                        </td>
                        <td align="left">
                            <a href="<?= $social->link ?>" target="_blank">
                                <?= $social->name ?></a>
                        </td>
                    </tr>
                <? } ?>
            <? } ?>
        </table>
    </div>
</div>
<div class="clear"></div>
<div class="col-lg-12">

    <table class="table">
        <tr>
            <td>Submitted:</td>
            <td><?= date(DEFAULT_DATETIME_FORMAT, $pend->created) ?></td>
        </tr>
        <tr>
            <td>Donation payment method:</td>
            <td><?= $pend->method_name ?></td>
        </tr>

        <tr>
            <td>Phone:</td>
            <td><?= $pend->phone ?></td>
        </tr>
        <tr>
            <td>Sent Payment To Account:</td>
            <td><?= $pend->account ?></td>
        </tr>
<!--        <tr>-->
<!--            <td>Your Account You Sent From:</td>-->
<!--            <td>--><?//= $pend->from_account ?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>Transaction ID:</td>-->
<!--            <td>--><?//= $pend->transaction_id ?><!--</td>-->
<!--        </tr>-->
        <tr>
            <td>Amount:</td>
            <td><?= money($pend->amount) ?></td>
        </tr>
    </table>

</div>
        <hr>
        <hr>
        <br>
        <br>
        <br>
    </div>

<?php } ?>
