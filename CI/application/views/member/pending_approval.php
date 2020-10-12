<? if (empty($pending)) { ?>

    <div class="alert alert-warning">No pending donations.</div>

<? } else { ?>
    <h1>Donations Pending Approval</h1>

    <div class="alert alert-warning">Upgrade disabled until all pending donations are approved or rejected.</div>

    <? foreach ($pending as $payer) { ?>

        <div id="payment<?= $payer->id ?>" class="col-lg-12" style="border-bottom: 1px solid #e8e8e8;">

            <h3><?= $payer->title ?> <?= money($payer->price) ?></h3>

            <div class="col-md-5">
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
                <div class="col-md-12">
                    <a href="<?=SITE_ADDRESS?>member/payment_update/<?=$payer->id?>/1" class="btn btn-alt btn-lg bg-success m-t-20 replace" data-confirm="Confirm approve donation from <?= $payer->userData->username ?>." data-div="payment<?= $payer->id ?>" style="width:100%">
                        <i class="fa fa-thumbs-up green" aria-hidden="true"></i>&nbsp;
                        APPROVE Donation</a>
                    <br/>
                    <br/>
                    <a href="<?= SITE_ADDRESS ?>member/payment_update/<?= $payer->id ?>/0" class="btn btn-alt btn-lg bg-danger m-t-20 m-b-20 replace" data-confirm="Confirm reject donation from <?=$payer->userData->username?>." data-div="payment<?= $payer->id ?>" style="width:100%">

                        REJECT Donation &nbsp;<i class="fa fa-thumbs-down red" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="col-md-7">
                <table class="table">
                    <tr>
                        <td>Time Remaining:</td>
                        <td><div class="countdown-display" data-secs="<?=$payer->created + CACHE_ONE_DAY - now()?>">
                            <?= displayCountDown($payer->created + CACHE_ONE_DAY - now()) ?>
                        </div></td>
                    </tr>
                    <tr>
                        <td>Submitted:</td>
                        <td><?= date(DEFAULT_DATETIME_FORMAT, $payer->created) ?></td>
                    </tr>
                    <tr>
                        <td>Method:</td>
                        <td><?= $payer->method_name ?></td>
                    </tr>
                    <tr>
                        <td>Donation Sent To:</td>
                        <td><?= $payer->account ?></td>
                    </tr>
                    <!--
                    <tr>
                        <td>Member's Account Donation Sent From:</td>
                        <td><?= $payer->from_account ?></td>
                    </tr>
                    -->
                    <tr>
                        <td>Transaction ID:</td>
                        <td><?= $payer->transaction_id ?></td>
                    </tr>
                    <tr>
                        <td>Amount USD:</td>
                        <td><?= money($payer->amount) ?></td>
                    </tr>
                    <? if ($payer->currency != 'USD') { ?>
                        <tr>
                            <td>Currency Sent:</td>
                            <td><?= $payer->currency_amount ?> <?= $payer->currency ?></td>
                        </tr>
                    <? } ?>
                    <tr>
                        <td>Transaction Details:</td>
                        <td><?= nl2br($payer->details) ?></td>
                    </tr>
                    <? if ($payer->proof_img) { ?>
                        <tr>
                            <td>Screenshot:</td>
                            <td>
                                <a href="#" data-featherlight="<?= SITE_ADDRESS ?>proofs/<?= $payer->proof_img ?>">
                                    <img src="<?= SITE_ADDRESS ?>proofs/<?= $payer->proof_img ?>" style="max-width: 50px" class="img-popup"/>
                                </a>
                            </td>
                        </tr>
                    <? } ?>

                </table>

            </div>
        </div>
    <? } ?>
<? } ?>
