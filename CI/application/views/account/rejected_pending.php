<h1>Donation Rejected</h1>

<div class="alert alert-danger">Your pending donation has been rejected.</div>

<a href="<?=SITE_ADDRESS?>support">Submit a support ticket</a>

<h2 class="tile-title">Rejected Donation <?= $pending->title ?> <?= money($pending->price) ?></h2>

<div class="col-lg-5">

    <table class="table">
        <tr>
            <td>Submitted:</td>
            <td><?= date(DEFAULT_DATETIME_FORMAT, $pending->created) ?></td>
        </tr>
        <tr>
            <td>Donation payment method:</td>
            <td><?= $pending->method_name ?></td>
        </tr>
        <tr>
            <td>Member Account You Sent To:</td>
            <td><?= $pending->account ?></td>
        </tr>
        <tr>
            <td>Your Account You Sent From:</td>
            <td><?= $pending->from_account ?></td>
        </tr>
        <tr>
            <td>Transaction ID:</td>
            <td><?= $pending->transaction_id ?></td>
        </tr>
        <tr>
            <td>Amount USD:</td>
            <td><?= money($pending->amount) ?></td>
        </tr>
        <? if ($pending->currency != 'USD') { ?>
            <tr>
                <td>Currency Sent:</td>
                <td><?= roundDown($pending->currency_amount, 2) ?> <?= $pending->currency ?></td>
            </tr>

        <? } ?>
    </table>
</div>
<div class="col-lg-7">

    <h3>Donation sent to:</h3>

    <div class="col-md-4 fs16">
        <? if ($payee->settings->show_avatar) { ?>
            <img src="<?= avatar($payee->avatar) ?>"/> <br/>
        <b><?= $payee->first_name ?> <?= $payee->last_name ?></b><br/>
           <?= $payee->username ?>
        <? } ?>
    </div>
    <div class="memberSponsor col-md-8">
            <table class="table">
                <? if ($payee->settings->show_email) { ?>
                    <tr>
                        <td>
                            <a href="mailto:<?= $payee->email ?>"><img src="<?= asset('images/social/email.png') ?>" style="width: 36px;"/></a>
                        </td>
                        <td align="left"><a href="mailto:<?= $payee->email ?>"><?= $payee->email ?></a></td>
                    </tr>
                <? } ?>
                <? if ($payee->settings->show_phone && !empty($payee->phone)) { ?>
                    <tr>
                        <td>
                            <img src="<?= asset('images/social/phone.png') ?>" style="width: 36px;"/>
                        </td>
                        <td align="left"><?= $payee->phone ?></td>
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


