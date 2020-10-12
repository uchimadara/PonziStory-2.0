<div class="refList">
    <? if (empty($referrals)) { ?>
    <div class="noReferrals m-l-15 floatLeft">No referrals</div>
    <? } else { ?>
    <? foreach ($referrals as $user) { ?>
        <? if (!isset($user->upline)) { ?>
            <div class="level-number">
                <span><?= $level ?></span>
            </div>
        <? } ?>
        <div class="referredUser <?=$levelNum?> admin-ref" id="ref-<?= $user->id ?>">
            <div class="refAvatar floatLeft">
                <? if ($user->settings->show_avatar != '0') { ?>
                <img src="<?= avatar($user->avatar) ?>"/>
                <? } ?>
                <div class="refUsername">
                    <? if ($this->isAdmin) { ?>
                        <a href="/admin/user/<?=$user->id?>"><?= $user->username ?></a>
                    <? }  else { ?>
                        <?= $user->username ?>
                    <? } ?>
                </div>
                <div class="stars">
                    <? for ($i=1; $i<=$user->account_level; $i++) { ?>
                    <i class="fa fa-star fs10 <? if ($i==1) echo 'silver'; ?>" aria-hidden="true"></i>
                        <? if ($i == 5) echo '<br />'; ?>
                    <? } ?>
                </div>
                <? if (isset($user->upline)) { ?>
                    <div>
                        Upline: <?=$user->upline?>
                    </div>
                <? } ?>
            </div>
            <div class="refDetails">
                <div class="refName">
                    <b><?= $user->first_name.' '.$user->last_name ?></b>
                </div>

                <div class="refAccountLevel">
                    <? if ($user->settings->show_email) { ?>
                        <?= $user->email ?><br/>
                    <? } ?>
                    Stage: <?= $user->account_level ?>
                </div>
                <div class="refCreated">

                    Joined: <?= date(DEFAULT_DATE_FORMAT, $user->created_on) ?>
                    <? if ($user->account_level == 0) { ?>
                    <br/> Expires: <?=date(DEFAULT_DATETIME_FORMAT, $user->account_expires)?>
                    <? } ?>
                </div>
                <div class="refReferrals">
                    <? if (!isset($user->upline) && $level < MAX_REF_LEVELS && $user->referrals > 0) { ?>
                        <a class="replaceClass" href="<?= SITE_ADDRESS ?>ajax/referrals/get_list/<?= $user->id ?>/<?=$level+1?>" id="ref-<?= $user->id ?>" data-id="<?= $user->id ?>" data-div="downline-<?= $user->id ?>">
                        Referrals: <?= $user->referrals ?>
                    </a>
                    <? } else { ?>

                        Referrals: <?= $user->referrals ?>
                    <? } ?>

                </div>
                <? if (isset($user->earning)) { ?>
                    <div>
                        Received Donations: <?= money($user->earning) ?>
                    </div>
                <? } ?>
            </div>
            <div class="refDetails">

                        <? if ($user->settings->show_email) { ?>
                                    <a href="mailto:<?= $user->email ?>">
                                        <img src="<?= asset('images/social/email.png') ?>" title="email" style="width: 36px;"/>
                                    </a>
                        &nbsp;
                        <? } ?>
                        <? if ($user->settings->show_phone && !empty($user->phone)) { ?>
                            <img src="<?= asset('images/social/phone.png') ?>" title="phone" style="width: 36px;"/>
                            <?=$user->phone?>
                            &nbsp;
                        <? } ?>
                        <? if (isset($user->settings->skype_id) && $user->settings->show_skype == 1) { ?>
                                <a href="skype:<?= $user->settings->skype_id ?>" title="skype">
                                        <img src="<?= asset('images/social/skype.png') ?>" style="width: 36px;"/>
                                    </a>
                            &nbsp;
                        <? } ?>

                        <? if (!empty($user->socialList)) { ?>
                            <? foreach ($user->socialList as $social) { ?>

                                        <a href="<?= $social->link ?>" target="_blank" title="<?=$social->name?>">
                                            <img src="<?= asset("images/social/".strtolower(str_replace('+', '-plus', $social->name)).".png") ?>" width="30px"/>
                                        </a>
                                &nbsp;
                            <? } ?>
                        <? } ?>
<!--                --><?php //var_dump($userData->username."-".$user->account_level) ?>


                <?php if($userData->account_level == 1 && $user->account_level == 0 && $userData->id == $user->referrer_id && now()>$user->account_expires ){ ?>
                    <br/>
                    <br/>
                    <a href="/back_office/refPurge/<?php echo $user->id ?>/<?php echo $userData->id ?>" class="btn btn-danger">Purge</a>
             <?php   } ?>

                <? if (ENABLE_INVITES && $userData->account_level > 0 && $user->account_level > 0) { ?>
                    <br/>
                    <br/>

                    <a class="popup fs13 m-t-10" title="Invite member to join as <?=$user->username?>'s referral" href="<?= site_url('member/form/invite/'.$user->id) ?>">
                        Invite member to join as <?=$user->username?>'s referral
                    </a>
                    <br/>
                    <b>Referral Link:</b> <?=SITE_ADDRESS?>ref/<?=$user->salt?>
                <? } ?>

            </div>
            <div class="clear"></div>
        </div>

        <? if ($level < MAX_REF_LEVELS && $user->referrals > 0) { ?>
            <div class="refDownline" id="downline-<?= $user->id ?>" ></div>
        <? } ?>

    <? } } ?>
</div>
