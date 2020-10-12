<? if (empty($pending)) { ?>

    <div class="alert alert-warning">No pending Payments.</div>

<? } else { ?>
    <h1>Payment Pending Approval</h1>

    <div class="alert alert-info">
      Confirm the Full details of the person(s) below then goto the Dashboard and view the POP and Either Confirm or Reject!
    </div>

    <? foreach ($pending as $payer) { ?>

        <div id="payment<?= $payer->id ?>" class="col-lg-12" style="border-bottom: 1px solid #e8e8e8;">

            <h3><?= $payer->title ?> : <?= money($payer->price) ?></h3>

            <div class="col-md-4">
                <div class="col-xs-4 fs16">
                    <? if ($payer->settings->show_avatar) { ?>
                        <img src="<?= avatar($payer->userData->avatar) ?>" style="max-width: 100px;"/> <br/>
                        <b><?= $payer->userData->first_name ?> <?= $payer->userData->last_name ?></b><br/>
                        <?= $payer->userData->username ?>

                    <? } ?>
                </div>
                <div class="memberSponsor col-xs-8">
                            <? if ($payer->settings->show_email) { ?>
                                    <a href="mailto:<?= $payer->userData->email ?>"><img src="<?= asset('images/social/email.png') ?>" style="width: 36px;"/></a>
                            &nbsp;
                            <? } ?>
                            <? if ($payer->settings->show_phone && !empty($payer->phone)) { ?>
                                <img src="<?= asset('images/social/phone.png') ?>" style="width: 36px;"/><?=$payer->phone?>
                                &nbsp;
                            <? } ?>

                            <? if (isset($payer->settings->skype_id) && $payer->settings->show_skype == 1) { ?>
                                <a href="skype:<?= $payer->settings->skype_id ?>">
                                        <img src="<?= asset('images/social/skype.png') ?>" style="width: 34px;"/>
                                    </a>
                                &nbsp;

                            <? } ?>
                            <? if (!empty($payer->socialList)) { ?>
                                <? foreach ($payer->socialList as $social) { ?>

                                        <a href="<?= $social->link ?>" target="_blank">
                                            <img src="<?= asset("images/social/".strtolower(str_replace('+', '-plus', $social->name)).".png") ?>" width="30px"/>
                                        </a>
                                    &nbsp;

                                <? } ?>
                            <? } ?>
                </div>
            </div>
            <div class="clear"></div>
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td>Amount:</td>
                        <td><?= money($payer->amount) ?></td>
                    </tr>
                    <tr>
                        <td>Elapsed Time:</td>
                        <td><div class="countup-display" data-secs="<?=$payer->created?>">
                            <?= displayCountDown(now() - $payer->created, true, true) ?>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Submitted:</td>
                        <td><?= date(DEFAULT_DATETIME_FORMAT, $payer->created) ?></td>
                    </tr>
                    <tr>
<!--                        <td>Confirmations:</td>-->
<!--                        <td>--><?//= $payer->confirmations ?><!--</td>-->
                    </tr>
                    <tr>
                        <td>Wallet Donation Sent To:</td>
                        <td><?= $payer->account ?></td>
                    </tr>
                    <!--
                    <tr>
                        <td>Member's Account Donation Sent From:</td>
                        <td><?= $payer->from_account ?></td>
                    </tr>
                    -->
                    <tr>
<!--                        <td>Transaction ID:</td>-->
<!--                        <td><span class="fs10">--><?//= $payer->transaction_id ?><!--</span></td>-->
                    </tr>
                </table>

            </div>
        </div>
    <? } ?>
<? } ?>
