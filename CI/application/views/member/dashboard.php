<h1>Member Dashboard</h1>
<h1 style="color: red">DISCLAIMER: THIS IS NOT A Federal Govt. SCHEME</h1>


<?php if ($fbNameCheck->fbname == "0"){ ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#modalSubscriptionForm').modal('show');
    });
</script>


<!-- Modal -->



<div class="modal fade" id="modalSubscriptionForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">FACEBOOK VERIFICATION TESTIMONIAL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open(site_url('member/fbUpdate'), array('method' => 'post')); ?>
            <div class="modal-body mx-3">
                <h3>Please Enter Your Correct Facebook name for Testimonial Verification</h3>
                <h4 style="color: red">Kindly Conform with this correctly to ensure you earn system bonus</h4>

                <div class="md-form mb-5">
                    <i class="fa fa-user prefix grey-text"></i>
                    <input type="text" name="fbname" id="form3" class="form-control validate">
                    <label data-error="wrong" data-success="right" for="form3">Your Facebook Name</label>
                </div>


            </div>
            <div class="modal-footer d-flex justify-content-center">
                <input type="submit" name="value_submit" class="btn btn-md btn-info" value="SAVE">            </div>
            </form>
        </div>
    </div>
</div>

<?php } ?>


<? if (isset($countdown) && $countdown > 0) { ?>

    <div class="col-md-12 alert alert-warning">
        <div class="col-md-12 fs20 alert alert-info">
            <h4 style="color: red;font-weight: bold">CONFUSED? NEW? THIS WHAT NEXT!</h4>
            <p style="font-size: 16px">1. <a href="<?= SITE_ADDRESS ?>back_office/accounts">Click Here</a>  to Add your Account Details</p>
            <p style="font-size: 16px">2. The name of your Sponsor/upline is <strong><?= $sponsor->first_name.' '.$sponsor->last_name ?></strong> </p>
            <p style="font-size: 16px">3. <a href="<?= SITE_ADDRESS ?>back_office/upgrade">Click Here</a>  to Pay your Sponsor the sum of NGN2,000 And Upload Your POP</p>
            <p style="font-size: 16px">4. Once the Countdown Timer below stops before Payment, Your Account will be Deleted</p>
            <p style="font-size: 16px">5. Once Your Payment have been confirmed,<a href="<?= SITE_ADDRESS ?>back_office/referrals">Click Here</a> to see your referral link </p>
            <p style="font-size: 16px">6. Finally, Use the link to Invite Just 2 people and start your millionaire Journey<a href="<?= SITE_ADDRESS ?>back_office/referrals">Click Here</a> to see your referral link </p>
            <br/>
            <p style="color: red;font-weight: bold;font-size: 18px">Your are currently a Free member</p>

        </div>
        <div class="col-md-12 m-t-20">

            <div class="countdown-display" data-secs="<?= $userData->created_on + (FREE_MEMBER_EXPIRE*CACHE_ONE_DAY) - now() ?>">
                <?= displayCountDown($userData->created_on + (FREE_MEMBER_EXPIRE*CACHE_ONE_DAY) - now()) ?>
            </div>
            <div class="m-t-10 center">
                You can't receive referrals or donations until you upgrade.
            </div>
        </div>
        <div class="clear"></div>
    </div>
<? } ?>
<?php //if ($dueUpgrade && $userData->account_level > 0){ ?>
<?php //if($userData->account_level > 0){ ?>
<!--<div class="memberSummary col-md-7">-->
<!--<h2 class="tile-title2" style="margin-top:0; text-align: ">-->
<!---->
<!--    <h4 style="color: red;font-weight: bold"><b style="color: red">WARNING!! </b>You have received Almost all your payment on this level.-->
<!---->
<!--    <div class="countdown-display" data-secs="--><?//=$userData->account_expires - now()?><!--">-->
<!--        --><?//= displayCountDown($userData->account_expires - now()) ?>
<!--    </div>-->
<!--        Quickly Upgrade So your account don't Expire before the timer elapse-->
<!--    </h4>-->
<!---->
<!--</h2>-->
<!--</div>-->
<?php //} ?>
<?php //} ?>

<?php //if ($dueUpgrade && $userData->account_level > 0){ ?>
<!--<div class="memberSummary col-md-5">-->
<!--    <div class="col-md-12 fs20 alert alert-info">-->
<!--        <h4 style="color: red;font-weight: bold"><b style="color: red">WARNING!! </b>You have received Almost all your payment on this level.-->
<!--        Quickly Upgrade so your Upline Don't Purge/Report you or the Timer Catching up on you!-->
<!--        </h4>-->
<!--    </div>-->
<!--</div>-->
<?php //} ?>

<div class="container">
    <div class="row">

<div class="memberSummary col-xs-7 col-sm-7 col-lg-7">
    <table class="table">
        <tr>
            <td class="center"><img src="<?= avatar($userData->avatar) ?>"/> <br/>
                <?php if($userData->recycle == "0"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Recruit</b>
                <?php }
                elseif ($userData->recycle == "1"){ ?>
                    Stage:(<?php echo $userData->recycle ?>) <b>Private</b>
                <?php }
                elseif ($userData->recycle == "2"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Private</b>
                <?php }
                elseif ($userData->recycle == "3"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Lance-Corporal</b>
                <?php }

                elseif ($userData->recycle == "4"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Corporal</b>
                <?php }

                elseif ($userData->recycle == "5"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Sergeant</b>
                <?php }

                elseif ($userData->recycle == "6"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Staff Sargeant</b>
                <?php }

                elseif ($userData->recycle == "7"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Warrant Officer 1</b>
                <?php }

                elseif ($userData->recycle == "8"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Warrant Officer 2</b>
                <?php }

                elseif ($userData->recycle == "9"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>2nd Lieutenant</b>
                <?php }

                elseif ($userData->recycle == "10"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Lieutenant</b>
                <?php }

                elseif ($userData->recycle == "11"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Captain</b>
                <?php }

                elseif ($userData->recycle == "12"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Major</b>
                <?php }

                elseif ($userData->recycle == "13"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Lieutenant Colonel</b>
                <?php }

                elseif ($userData->recycle == "14"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Colonel</b>
                <?php }

                elseif ($userData->recycle == "15"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Brigadier General</b>
                <?php }

                elseif ($userData->recycle == "16"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Major General</b>
                <?php }

                elseif ($userData->recycle == "17"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Lieutenant General</b>
                <?php }

                elseif ($userData->recycle == "18"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>General</b>
                <?php }

                elseif ($userData->recycle == "19"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Field Marshal</b>
                <?php }

                elseif ($userData->recycle >= "20"){ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Legend</b>
                <?php }
                else{ ?>
                Stage:(<?php echo $userData->recycle ?>) <b>Suspect</b>
            <?php } ?>


                </td>

            <td>
                <? if ($userData->locked == 1) { ?>
                    <img src="<?=asset('images/lock.png')?>" />&nbsp;
                    <span class="alert alert-danger alert-narrow">Account locked.</span><br/>
                    Submit a <a href="<?= SITE_ADDRESS ?>support">Support Ticket</a> to unlock your account or contact livechat
                    <p style="font-size: 15px;color:red">Query: <?php if(!empty($reason->reason)) {echo $reason->reason; } else {echo $reasons2->reason;}?></p>
                <? } else { ?>
                    <? if (empty($wallet)) { ?>
                        <a href="<?= SITE_ADDRESS ?>back_office/accounts" class="btn btn-alt m-b-5" style="font-size: 16px">Add your Account Details to receive withdrawals</a>
                        <br/>
                    <? } ?>
<!--                    --><?// if (intval($userData->account_level) < intval(MAX_REF_LEVELS)) { ?>
<!--                        <a href="--><?//= SITE_ADDRESS ?><!--back_office/upgrade" class="btn btn-alt m-b-5" style="font-size: 25px">Upgrade to next stage</a>-->
<!--                        <br/>-->
<!--                    --><?// } ?>
                    <a href="<?= SITE_ADDRESS ?>back_office/profile" class="btn btn-alt" style="font-size: 23px">Edit profile</a>

            <td class="center"> <a href="//t.me/tradermonichannel"><img src="/assets/images/telegram.png" alt="tradermoni TELEGRAM" height="50"  target="_blank"></a> <br/><small style="font-weight: bold">Offical Telegram Channel</small></td>
            <td class="center"> <a href="//tradermoni.net/teams"><img src="/assets/images/team.jpg" alt="tradermoni TEAMS" height="50"  target="_blank"></a> <br/><small style="font-weight: bold">Join A Team & Be Guided</small></td>

                <? } ?>
            </td>
        </tr>
        <tr style="font-size: 23px">
            <td>Joined:</td>
            <td><?=date(DEFAULT_DATE_FORMAT, $userData->created_on)?></td>
        </tr>
        <tr style="font-size: 23px">
            <td>Last login:</td>
            <td><?= date(DEFAULT_DATE_FORMAT, $userData->last_login) ?></td>
        </tr>
<!--        <tr style="font-size: 23px">-->
<!--            <td>--><?//= $payments['sent']->c ?><!-- Donations Sent:</td>-->
<!--            <td>--><?//= money($payments['sent']->total)  ?><!--</td>-->
<!--        </tr>-->
<!--        <tr style="font-size: 23px">-->
<!--            <td>--><?//= $payments['received']->c ?><!-- Donations Received:</td>-->
<!--            <td>--><?//= money($payments['received']->total) ?><!--</td>-->
<!--        </tr>-->
        <? if ($payments['pending']->c > 0) { ?>
            <tr style="font-size: 23px">
                <td><?= $payments['pending']->c ?> Pending Donations:</td>
                <td> for <?= money($payments['pending']->total) ?></td>
            </tr>
        <? } ?>
    </table>

</div>



<? if (isset($sponsor)) { ?>
    <div class="memberSponsor col-xs-5 col-sm-5 col-5">
        <h2 class="tile-title" style="margin-top:0;">Your Guider: <?= $sponsor->first_name.' '.$sponsor->last_name ?></h2>

        <div class="col-md-4 center">
            <? if ($sponsorSettings->show_avatar) { ?>
            <img src="<?= avatar($sponsor->avatar) ?>"/> <br/>
                     <b><?= $sponsor->username ?></b>
            <? } ?>
            <div class="stars mTop5">
                <? for ($i = 1; $i <= $sponsor->account_level; $i++) { ?>
                    <i class="fa fa-star fs10 <? if ($i == 1) echo 'silver'; ?>" aria-hidden="true"></i>
                    <? if ($i == 5) echo '<br />'; ?>
                <? } ?>
            </div>

        </div>


        <div class="col-md-8 center">
            <table class="table">
                <? if ($sponsorSettings->show_email) { ?>
                <tr>
                    <td><a href="mailto:<?= $sponsor->email ?>"><img src="<?=asset('images/social/email.png')?>" style="width: 36px;" /></a></td>
                    <td align="left"><a href="mailto:<?=$sponsor->email?>"><?= $sponsor->email ?></a></td>
                </tr>
                <? } ?>
                <? if ($sponsorSettings->show_phone && !empty($sponsor->phone)) { ?>
                    <tr>
                        <td>
                            <img src="<?= asset('images/social/phone.png') ?>" style="width: 36px;"/>
                        </td>
                        <td align="left"><?= $sponsor->phone ?></td>
                    </tr>
                <? } ?>
                <? if (isset($sponsorSettings->skype_id) && $sponsorSettings->show_skype == 1) { ?>
                    <tr>
                        <td><a href="skype:<?= $sponsorSettings->skype_id ?>">
                                <img src="<?= asset('images/social/skype.png') ?>" style="width: 34px;"/>
                            </a>
                        </td>
                        <td align="left">
                            <a href="skype:<?= $sponsorSettings->skype_id ?>">
                            <?= $sponsorSettings->skype_id ?></a>
                        </td>
                    </tr>
                <? } ?>

                <? if (!empty($sponsorSocialList)) { ?>
                    <? foreach ($sponsorSocialList as $social) { ?>
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
            <br>
            <hr>
            <?php if(!$teamlegi && $member_cycle > 2){ ?>
                <tr>
                    <td>
                        <a href="/back_office/teams/add"  style="line-height: 40px" class="btn btn-md btn-sm btn-danger">CLICK Here To Add Your Team </a>

                    </td>
                </tr>
            <?php } ?>
        </div>

    </div>
    </div>
    </div>

    <div class="col-lg-12 alert alert-success">
<div class="memberSponsor col-xs-4 col-sm-4 col-lg-4">

<?php $withdrawalLimit = 500000; ?>
<!--        --><?//= form_open(site_url('/member/mergeme'), array('method' => 'post',  'id' => 'loginFrm')); ?>

        <!--                                <form name="frm"  id="frm" method="post" action="/adminpanel/merger/bulkMerge">-->
        <input type="hidden" name="idee" class="method" value="<?php echo $enc_gh_status ?>" />
        <input type="hidden" name="pid" class="method" value="<?php echo $getMeMerge->id ?>" />
        <input type="hidden" name="mid" class="method" value="<?php echo $getMeMerge->method_id ?>" />


        <? if ($userData->locked == 1) { ?>
            <img src="<?=asset('images/lock.png')?>" />&nbsp;
            <span class="alert alert-danger alert-narrow">Account locked.</span><br/>
            Submit a <a href="<?= SITE_ADDRESS ?>support">Support Ticket</a> to unlock your account or contact livechat
            <p style="font-size: 15px;color:red">Query: <?php if(!empty($reason->reason)) {echo $reason->reason; } else {echo $reasons2->reason;}?></p>
        <? }

        elseif(date('D') == 'Sat' || date('D') == 'Sun') { ?>
            <div class="alert alert-warning">No Merging on Saturday or Sunday or Public Holiday</div>
       <? }

        else { ?>
            <?php if ($getMeMerge->status == "1") { ?>

                <?php if($todayGhSum < $withdrawalLimit ){ ?>

                <input class="btn btn-alt m-r-10" style="line-height: 50px; font-size: 28px;font-weight: bold" type="submit" name="submit" value="MERGE ME">

            <?php } else{ ?>
                    <input class="btn btn-alt m-r-10" onclick="return alert('Todays Withdrawal limit have been reached. Try Tomorrow')" style="line-height: 50px; font-size: 28px;font-weight: bold" type="button"  value="MERGE ME">

              <?php  } ?>
            <?php } ?>



        <?php } ?>



        </form>
</div>


<div class="memberSponsor col-xs-4 col-sm-4 col-lg-4">


    <a href="#">  <button type="button" data-toggle="modal" data-target="#sysbonus" style="line-height: 40px; font-size: 20px;font-weight: bold;padding: 0px;margin: 0px"  class="btn btn-danger btn-sm"><small style="display: block;width: 150px;word-wrap: break-word;white-space: normal;">System Bonus  (<?php echo money($bonus_sys*5) ?>)</small></button> </a>

</div>

        <div class="memberSponsor col-xs-4 col-sm-4 col-lg-4">


            <div class="alert alert-warning">

                <small style="line-height: 30px; font-size: 14px;font-weight: bold;">Daily Withdrawal Limit:<strong>  <?php  echo money($withdrawalLimit); ?></strong></small> <br>
                <small style="line-height: 30px; font-size: 14px;font-weight: bold;">Todays Withdrawal Total:<strong> <?php  echo money($todayGhSum); ?> </strong></small>
            </div>
        </div>


        <div class="modal fade" id="sysbonus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">WITHDRAW SYSTEM BONUS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <br>

                    <?php


                    if ($sysBonCount > 3){ ?>
                        <form name="frmm" id="frmm" method="post" action="/member/insertSYSBonus">


                            <div class="modal-body mx-3">
                                <p style="text-align: center">Click submit to withdraw the sum of <b><?php echo money($bonus_sys*4);  ?></b></p>

                                <br>
                                <p style="color: orangered">Note:If you want to upgrade to Next plan, Tick below</p>
                                <p style="color: red">Note 2:You can only change plan after every 4 withdrawals</p>
                                <div class="radio" style="padding: 3px;margin: 3px">
                                    <br>
                                 <label style="float: left;margin: 5px"> Upgrade to Next Plan  <input type="radio"  style="transform: scale(2);margin: 3px;"  name="optradion"  value="<?php echo $CurrentPlan->plan_id + 1 ?>"></label>
                                </div>
                            </div>
                            <input type="hidden" name="amount" value="<?php echo $bonus_sys*4;  ?>">

                            <div class="modal-footer d-flex justify-content-center">
                                <input class="btn btn-alt m-r-10" type="submit" name="submit" value="SUBMIT">
                            </div>
                        </form>
                    <?php } else { ?>

                        <p style="color: red;font-size: 16px;font-weight: bold;text-align: center">Your Recycle Count is <?php echo $sysBonCount ?></p>
                        <p style="color: red;font-size: 16px;font-weight: bold;text-align: center">You must have at least 4 to withdraw your System Bonus</p>


                    <?php } ?>
                    <br>
                    <br>
                </div>
            </div>
        </div>




    </div>
<br>
<hr>
    <div class="clear"></div>
    <?php  if($pending){?>
    <div class="col-lg-12 center">
        <div class="alert alert-warning" style="font-size: large;font-weight: bold">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            You have been Merged to Invest, Click on Make Investment to see your partner. You have limited time

        </div>
<!--        --><?//if ($dat->payee_user_id == $userData->id) { ?>
<!--            <div class="col-lg-6 center">-->
<!--                <h3 style="color: red">Random Absolute Recommitment (RAR)</h3>-->
<!--          <a href="/back_office/upgrade">  <button type="button" style="line-height: 50px; font-size: 36px;font-weight: bold"  class="btn btn-warning btn-md">MAKE RECOMMITMENT</button></a>-->
<!--            </div>-->
<!--            --><?php //}else { ?>
        <?php foreach ($pending as $pend){ ?>


            <div class="col-lg-7 center" style="margin-bottom: 5px">
                <a href="/back_office/upgrade">  <button type="button" style="line-height: 50px; font-size: 36px;font-weight: bold"  class="btn btn-warning btn-md">MAKE INVESTMENTS</button></a>
            </div>
<!--            --><?php //} ?>
            <div class="col-lg-5 center" style="margin-bottom: 5px">
            <div class="countdown-display" style="width: 300px; height: 60px;float right" data-secs="<?=$pend->expired - now()?>">
                <?= displayCountDown($pend->expired - now()) ?>
            </div>
            </div>
        <?php } ?>

        </div>
    </div>
    <?php } ?>

<? } ?>



<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">INVEST</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php if ($ph_exist > 0){ ?>
                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">Warning: You already have an existing Investment, Withdraw it First!</p>
                <br>
                <br>
 <?php }
            elseif($gh_exist2 > 0){ ?>
                <br>
                <br>
                <form name="frmm" id="frmm" method="post" action="/member/ReinsertPH">


                    <div class="modal-body mx-3">
                        <?php
                        //$recom = (int)$lastRecom->amount;
                        if($lastRePH->plan_id == "1") {
                            $ph = 5000;
                            $recom = 2500;
                            $diff = $ph - $recom;
                        }

                        if($lastRePH->plan_id == "2") {
                            $ph = 10000;
                            $recom = 5000;
                            $diff = $ph - $recom;
                        }


                        ?>
                        <p style="text-align: center">You Have Already Recommitted <b style="color: orangered"> <?php echo money($recom) ?> </b></p>
                        <p style="text-align: center">You are to pay <b style="color: orangered"> <?php echo money($diff) ?> Instead of <?php echo money($ph) ?> </b></p>
                        <p style="text-align: center">Click Invest Below to INVEST AGAIN</p>

                    </div>
                    <input type="hidden" name="amount" value="<?php echo $diff ?>">

                    <br>
                    <small style="color: red">Note: You can only change plan after 5 withdrawals</small>
                    <div class="modal-footer d-flex justify-content-center">
                        <input class="btn btn-alt m-r-10" type="submit" name="submit" value="INVEST">
                    </div>

                </form>                <br>
                <br>

            <?php }

 else { ?>
             <?php if (empty($wallet)) { ?>
                 <br/>
                     <p style="text-align: center">   <a href="<?= SITE_ADDRESS ?>back_office/accounts" class="btn btn-alt m-b-5" style="font-size: 16px">Add your Account Details to receive withdrawals</a></p>
                        <br/><br/>

           <?php } else{ ?>
                <form name="frmm" id="frmm" method="post" action="/member/insertPH">

                    <?php if($testi < $userData->recycle){ ?>
                        <br>
                        <br>
                        <br>
                    <p style="text-align: center;color: red;font-weight: bold">Warning: Please Add your Testimony of your previous Transactions
                        <a class="btn btn-alt" id="show-testimonia" title="Add Testimonial"  href="/back_office/testimonials">
                            Click here to Add
                        </a></p>
                        <br>
                        <br>
                        <br>
                    <?php } else { ?>
                    <div class="modal-body mx-3">
                        <p style="text-align: center">Select a plan and Click Submit</p>


                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Plan</th>
                                <th>Invest</th>
                                <th>Recommit</th>
                                <th>Sys Bonus</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(empty($CurrentPlan->plan_id)) { ?>
                            <tr>
                                <td><div class="radio">
                                        <input type="radio"  style="transform: scale(2)"  name="optradio"  value="5000">
                                    </div></td>
                                <td>Starter</td>
                                <td> N5,000 </td>
                                <td>N2500</td>
                                <td>N2500</td>
                            </tr>
                            <?php }else{ ?>
                            <tr>
                                <td><div class="radio">
                                    <input type="radio"  style="transform: scale(2)" <?php if($CurrentPlan->plan_id != 1) { ?> disabled="disabled" <?php } ?> name="optradio"  value="5000">
                                </div></td>
                                <td>Starter</td>
                                <td> N5,000 </td>
                                <td>N2500</td>
                                <td>N2500</td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td><div class="radio">
                                    <input type="radio" style="transform: scale(2)" <?php if($CurrentPlan->plan_id != 2) { ?> disabled="disabled" <?php } ?> name="optradio"  value="10000">
                                </div></td>
                                <td>Bronze</td>
                                <td> N10,000</td>
                                <td>N5,000</td>
                                <td>N5,000</td>
                            </tr>


                            </tbody>
                        </table>



                    </div>
                    <?php } ?>

                    <?php if(!($testi < $userData->recycle)){ ?>
                    <div class="modal-footer d-flex justify-content-center">
                        <input class="btn btn-alt m-r-10" type="submit" name="submit" value="SUBMIT">
                    </div>
                    <?php } ?>
                </form>

            <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>


<div class="modal fade" id="RecommitForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">RECOMMITMENT</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php if ($ph_exist2 > 0){ ?>

                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">Warning: You already have an existing Recommitment</p>
                <br>
                <br>

            <?php }

             elseif (!$ph_exist > 0){ ?>

                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">You need to Invest First before Recommitment</p>
                <br>
                <br>

            <?php }

            elseif ($check4Recom > 0){ ?>

                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">You Must first Pay investment before Recommitting</p>
                <br>
                <br>

            <?php }
            else { ?>
                <?php if (empty($wallet)) { ?>
                    <br/>
                    <p style="text-align: center">   <a href="<?= SITE_ADDRESS ?>back_office/accounts" class="btn btn-alt m-b-5" style="font-size: 16px">Add your Account Details to receive withdrawals</a></p>
                    <br/><br/>

                <?php } else{ ?>
                    <form name="frmm" id="frmm" method="post" action="/member/insertRecom">


                            <div class="modal-body mx-3">
                                <p style="text-align: center">The Recommitment Value of your Plan is <b style="color: orangered"> <?php echo money($recomvalue) ?> </b></p>

                            </div>
                            <input type="hidden" name="amount" value="<?php echo $recomvalue ?>">



                            <div class="modal-footer d-flex justify-content-center">
                                <input class="btn btn-alt m-r-10" type="submit" name="submit" value="RECOMMIT">
                            </div>

                    </form>
                <?php } ?>

            <?php } ?>
        </div>
    </div>
</div>


<?php
$rone = "Your Recommitment of ". money($ph_status2->amount) ." is awaiting merging!";
$rtwo = "Your Recommitment have been merged, Proceed to pay your partner";
$rthree = "Your partner is yet to confirm your Payment, Please Be Patient!";
$rfour = "Your Recommitment of ".money($ph_status2->amount)." have confirmed!!!";
$rsix = "Problem with confirmation! One of you reported! Accounts on Hold";




$gone = "Your Withdrawal of ". money($gh_status->rem_amount) ." is awaiting merging, Please Stay tuned!";
$gtwo = "Your Withdrawal have been merged, Call your Partner!";
$gthree = "Your Withdrawal have been Payed,Verify and Confirm your partner!";
$gsix = "Problem with confirmation! One of you reported! Accounts on Hold";
?>
<div class="container">
    <div class="row">
<div class="col-lg-12">


<div class="memberSummary col-xs-4 col-sm-4 col-lg-4">
    <?php if($getStatusInfo) {
        $info = '<div class="alert alert-warning"><strong>Info: </strong><a href="/back_office/upgrade"> Click here to see who</a></div>';

    }?>
    <?php if($ph_statusM){ ?>
    <?php foreach ($ph_statusM as $status){

        $pone = "Your investment of ". money($status->rem_amount) ." is awaiting merging, Stay tuned!";
        $ptwo = "Your Investment have been merged, Proceed to pay your partner";
        $pthree = "Your partner is yet to confirm your Payment, Please Be Patient!";
        $pfour = "Your Investment of ".money($status->amount)." have confirmed!!!";
        $psix = "Problem with confirmation! One of you reported! Accounts on Hold";
        ?>


        <?php  if ($status->status == "1"){?>
    <div class="alert alert-warning">
        <strong>Status: </strong> <?php  echo $pone; ?>
    </div>
    <?php } ?>

    <?php  if ($status->status == "2"){?>
        <div class="alert alert-warning">
            <strong>Status: </strong> <?php  echo $ptwo; ?>
        </div>
    <?php } ?>

    <?php  if ($status->status == "3"){?>
        <div class="alert alert-warning">
            <strong>Status: </strong> <?php  echo $pthree; ?>
        </div>
    <?php } ?>

    <?php  if ($status->status == "4"){?>
        <div class="alert alert-warning">
            <strong>Status: </strong> <?php  echo $pfour; ?>
        </div>
    <?php } ?>
    <?php  if ($status->status == "6"){?>
        <div class="alert alert-warning">
            <strong>Status: </strong> <?php  echo $psix; ?>
        </div>
    <?php } ?>
    <?php } ?>

            <?php  echo $info; ?>

    
    <?php } ?>


    <? if ($userData->locked == 1) { ?>
        <img src="<?=asset('images/lock.png')?>" />&nbsp;
        <span class="alert alert-danger alert-narrow">Account locked.</span><br/>
        Submit a <a href="<?= SITE_ADDRESS ?>support">Support Ticket</a> to unlock your account or contact livechat
        <p style="font-size: 15px;color:red">Query: <?php if(!empty($reason->reason)) {echo $reason->reason; } else {echo $reasons2->reason;}?></p>
    <? } else { ?>

    <button type="button" style="line-height: 50px; font-size: 36px;font-weight: bold" data-toggle="modal" data-target="#modalLoginForm"  class="btn btn-info btn-lg btn-block">INVEST</button>


    <?php } ?>
</div>


    <div class="memberSummary col-xs-4 col-sm-4 col-lg-4">
        <?php  if ($ph_status2->status == "1"){?>
            <div class="alert alert-warning">
                <strong>Status: </strong> <?php  echo $rone; ?>
            </div>
        <?php } ?>

        <?php  if ($ph_status2->status == "2"){?>
            <div class="alert alert-warning">
                <strong>Status: </strong> <?php  echo $rtwo; ?>
            </div>
        <?php } ?>

        <?php  if ($ph_status2->status == "3"){?>
            <div class="alert alert-warning">
                <strong>Status: </strong> <?php  echo $rthree; ?>
            </div>
        <?php } ?>

        <?php  if ($ph_status2->status == "4"){?>
            <div class="alert alert-warning">
                <strong>Status: </strong> <?php  echo $rfour; ?>
            </div>
        <?php } ?>
        <?php  if ($ph_status2->status == "6"){?>
            <div class="alert alert-warning">
                <strong>Status: </strong> <?php  echo $rsix; ?>
            </div>
        <?php } ?>


        <? if ($userData->locked == 1) { ?>
            <img src="<?=asset('images/lock.png')?>" />&nbsp;
            <span class="alert alert-danger alert-narrow">Account locked.</span><br/>
            Submit a <a href="<?= SITE_ADDRESS ?>support">Support Ticket</a> to unlock your account or contact livechat
            <p style="font-size: 15px;color:red">Query: <?php if(!empty($reason->reason)) {echo $reason->reason; } else {echo $reasons2->reason;}?></p>
        <? } else { ?>

                <button type="button" style="line-height: 50px; font-size: 36px;font-weight: bold" data-toggle="modal" data-target="#RecommitForm"  class="btn btn-warning btn-lg btn-block">RECOMMIT</button>


        <?php } ?>
    </div>


    <div class="memberSponsor col-xs-4 col-sm-4 col-lg-4">

        <?php  if ($gh_status->status == "1"){?>
          <div class="alert alert-success">
                <strong>Status: </strong> <?php  echo $gone; ?>
            </div>
        <?php } ?>

        <?php  if ($gh_status->status == "2"){?>
            <div class="alert alert-success">
                <strong>Status: </strong> <?php  echo $gtwo; ?>
            </div>
        <?php } ?>

        <?php  if ($gh_status->status == "3"){?>
            <div class="alert alert-success">
                <strong>Status: </strong> <?php  echo $gthree; ?>
            </div>
        <?php } ?>

        <?php  if ($gh_status->status == "6"){?>
            <div class="alert alert-success">
                <strong>Status: </strong> <?php  echo $gsix; ?>
            </div>
        <?php } ?>

        <? if ($userData->locked == 1) { ?>
            <img src="<?=asset('images/lock.png')?>" />&nbsp;
            <span class="alert alert-danger alert-narrow">Account locked.</span><br/>
            Submit a <a href="<?= SITE_ADDRESS ?>support">Support Ticket</a> to unlock your account or contact livechat
            <p style="font-size: 15px;color:red">Query: <?php if(!empty($reason->reason)) {echo $reason->reason; } else {echo $reasons2->reason;}?></p>
        <? } else { ?>
        <button type="button" style="line-height: 50px; font-size: 36px;font-weight: bold"  data-toggle="modal" data-target="#modalLogForm" class="btn btn-success btn-lg btn-block">WITHDRAW</button>
 <?php } ?>
    </div>

</div>
</div>
</div>



<?if ($dat->payee_user_id == $userData->id) { // var_dump($dis)?>
    <div class="col-lg-12">
        <div class="tile">

            <h2 class="tile-title">Awaiting Confirmation</h2>
            <table class="table">
                <h3 style="color: orangered">BEFORE REJECTING A PAYMENT, CALL THE PERSON TO BE SURE OR WRITE TO SUPPORT</h3>
<!--                <h3 style="color: red">Your Account will be blocked if you don't Accept or Reject Payment before time runs out</h3>-->
                <h3 style="color: red">You Can only Reject payment when the countdown is below 10hours. So call your Partner and be patient</h3>
                <h3 style="color: red">Do not Click CONFIRM BUTTON if you haven't been Paid yet</h3>
                <tr>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Amount(₦)</th>
                    <th>POP</th>
                    <th>Time Remaining</th>
                    <th>Action/Reaction</th>
                </tr>
                <? foreach ($dataa as $dut) { ?>
                    <tr>
                        <td data-th="Username"><?=$dut->username?></td>
                        <td data-th="Phone"><?=$dut->phone?></td>
                        <td data-th="Amount"><?= money($dut->amount) ?></td>
                        <?php if (isset($dut->proof_img)){ ?>
                        <td>  <a href="<?=base_url()?>proofs/<?= $dut->proof_img ?>" class="popupImg"><img src="<?=base_url()?>proofs/<?= $dut->proof_img ?>" width="60px" height="60px"></a></td>
                        <?php } else {?>
                            <td>     <a href="#" disabled="disabled"  style="line-height: 40px" class="btn btn-md btn-sm btn-danger">POP not Uploaded Yet</a>
                            </td>
                        <?php } ?>
                        <td><div class="countdown-display" data-secs="<?=$dut->expired - now()?>">
                                <?= displayCountDown($dut->expired - now()) ?>
                            </div></td>
                        <td> <?php $ng = $dut->expired - now();  ?>
                            <? if (@$$dut->created - now() < (CACHE_ONE_DAY*5)) { ?>
                                <a href="<?= SITE_ADDRESS ?>member/approve/<?= $dut->payer_user_id ?>/<?= $dut->id ?>" onclick="return confirm('You are about to confirm Payment. This action is irreversible!!  Are you Sure?')" style="line-height: 40px" class="btn btn-md btn-sm btn-alt ">Confirm</a>
                                <?php if(36000 > $ng){ //10 hours to go will see reject button ?>
                                <a href="<?= SITE_ADDRESS ?>member/reject/<?= $dut->payer_user_id ?>/<?= $dut->id ?>"  style="line-height: 40px" class="btn btn-md btn-sm btn-danger">Reject</a>
                            <? }else { ?>
<!--                                    <a href="#" onclick="return confirm('You cannot Reject Payment until its 10 hours to end, SO BE PATIENT AND CALL YOUR PARTNER ')"  style="line-height: 40px" class="btn btn-md btn-sm btn-danger">Reject</a>-->

                                <? } ?>
                                <? } ?>
                        </td>
                    </tr>
                <? } ?>
            </table>

            <b>Note: </b> You can only withdraw when you have successfully Invested
        </div>
    </div>
<? } ?>




<div class="modal fade" id="modalLogForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">WITHDRAW</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php

            $n = $las->date_of_gh;
            $today = time();
            $oneWeek = strtotime("$n");
            $secs = $oneWeek - $today;
            $days = $secs/86400;
            $d = floor($days);
            //echo $d;
            $date_now = date("Y-m-d"); // this format is string comparable


//if($testi < $userData->recycle){ ?>
<!--            <br>-->
<!--            <br>-->
<!--            <br>-->
<!--            <p style="text-align: center;color: red;font-weight: bold">Warning: Please Add your Testimony of your previous Transactions-->
<!--                <a class="btn btn-alt" id="show-testimonia" title="Add Testimonial"  href="/back_office/testimonials">-->
<!--                    Click here to Add-->
<!--                </a></p>-->
<!--            <br>-->
<!--            <br>-->
<!--            <br>-->
<!--            --><?php //}
            if  (!$lastPH->amount){ ?>
                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">WARNING: You are not Eligible to Withdraw yet! Check your Investment Status</p>

                <br>
                <br>
            <?php } elseif($gh_exist > 0){ ?>

                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">Warning: You already have an existing Withdrawal Request! Please stay Tuned!</p>
                <br>
                <br>

<!--            --><?php //}
//            elseif($gh_collected > 0){ ?>
<!---->
<!--                <br>-->
<!--                <br>-->
<!--                <p style="text-align: center;color: red;font-weight: bold">Warning: You still have --><?php //echo $d+1?><!-- days left OR You have already collected this withdrawals</p>-->
<!--                <br>-->
<!--                <br>-->
            <?php }
                elseif($n > $date_now){ ?>
                    <br>
                    <br>
            <p style="text-align: center;color: red;font-weight: bold">You still have <?php echo $d+1?> days left</p>
                    <br>
                    <br>


          <?php }

            elseif($getGhRecord4Testi < $testiCount){ ?>
                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">You must Share Testimony Before Next Withdraw - <a href="/back_office/testimonials"> Click here to share </a></p>
                <br>
                <br>

            <?php }

            elseif(!$lastPH2->amount){ ?>
                <br>
                <br>
                <p style="text-align: center;color: red;font-weight: bold">Warning: You Must Recommit before Withdrawing! Click Recommit,Pay and be confirmed before Withdraw</p>
                <br>
                <br>


            <?php }
          else{ ?>
                <form name="frmm" id="frmm" method="post" action="/member/insertGH">


                    <div class="modal-body mx-3">
                        <p style="text-align: center">Click submit to withdraw the sum of <b><?php echo "N".$gh_amt;  ?></b></p>



                    </div>
                    <input type="hidden" name="amount" value="<?php echo $gh_amt ?>">

                    <div class="modal-footer d-flex justify-content-center">
                        <input class="btn btn-alt m-r-10" id="postSub" type="submit"  name="submit" value="SUBMIT">
                    </div>
                </form>

            <?php } ?>
        </div>
    </div>
</div>



<?php if($punishment->payer_user_id == $userData->id) { // var_dump($dis)?>
    <div class="col-lg-12">
        <div class="tile" style="color: orangered">

            <h2 class="tile-title" style="color: orangered">PUNISHMENT</h2>
            <table class="table">
                <h3 style="color: orangered">To Unlock your account, Call the person you defaulted below and Send the Recharge Value below</h3>
                <!--                <h3 style="color: red">Your Account will be blocked if you don't Accept or Reject Payment before time runs out</h3>-->
                <tr>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Amount(₦)</th>
                    <th>Time Remaining</th>
                </tr>

                    <tr>
                        <td data-th="Username"><?=$punishment->username?></td>
                        <td data-th="Phone"><?=$punishment->phone?></td>
                        <td data-th="Amount"><? if($punishment->amount < "5001"){echo "500";}else{ echo "1000";} ?></td>

                        <td><div class="countdown-display" data-secs="<?=$punishment->expired - now()?>">
                                <?= displayCountDown($punishment->expired - now()) ?>
                            </div></td>

                    </tr>

            </table>

            <b>Note: </b> Once Your partner Confirms your Recharge Card, You Account will be unlocked Automatically
        </div>
    </div>

<? } ?>


<?if ($punishment2->payee_user_id == $userData->id) { // var_dump($dis)?>
    <div class="col-lg-12">
        <div class="tile" style="color: orangered">

            <h2 class="tile-title" style="color: orangered">DEFAULTER COMPENSATION</h2>
            <table class="table">
                <h3 style="color: orangered">The Person Listed below defaulted payment to you and served a punishment to send you recharge card</h3>
                <h3 style="color: orangered">Kindly Confirm the Recharge Card once you see it</h3>
                <!--                <h3 style="color: red">Your Account will be blocked if you don't Accept or Reject Payment before time runs out</h3>-->
                 <tr>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Amount(₦)</th>
                    <th>Time Remaining</th>
                    <th>Action/Reaction</th>
                </tr>

                <?php foreach($punishment3 as $pun){ ?>
                    <tr>
                        <td data-th="Username"><?=$pun->username?></td>
                        <td data-th="Phone"><?=$pun->phone?></td>
                        <td data-th="Amount"><? if($pun->amount < "5001"){echo "500";}else{ echo "1000";} ?></td>

                        <td><div class="countdown-display" data-secs="<?=$pun->expired - now()?>">
                                <?= displayCountDown($pun->expired - now()) ?>
                            </div></td>
                        <td> <?php $ng = $pun->expired - now()  ?>
                            <? if (@$$pun->expired - now() < (CACHE_ONE_DAY*5)) { ?>
                                <a href="<?= SITE_ADDRESS ?>member/approve_punishment/<?= $pun->payer_user_id ?>/<?= $pun->id ?>" onclick="return confirm('You are about to confirm Payment For Punishment. This action is irreversible!!  Are you Sure?')" style="line-height: 40px" class="btn btn-md btn-sm btn-alt ">Confirm</a>

                                    <a href="<?= SITE_ADDRESS ?>member/reject_punishment/<?= $pun->payer_user_id ?>/<?= $pun->id ?>"  style="line-height: 40px" class="btn btn-md btn-sm btn-danger">Reject</a>

                            <? } ?>
                        </td>
                    </tr>
                <?php } ?>

            </table>

            <b>Note: </b> You can only withdraw when you have successfully Invested
        </div>
    </div>
<? } ?>



<div class="col-lg-12">
    <br>
    <br>
    <br>
    <div class="tile">

        <h2 class="tile-title">tradermoni - POTENTIAL REFERRAL BONUS SUMMARY</h2>

<a href="/back_office/bonus"><p style="color: orangered;text-align: center;font-weight: bold;font-size: medium"> CLick here to see full table of referral Bonus</p></a>

        <div id="memberSummary">
            <table class="table" style="font-size: 18px">
                <tbody><tr>
                    <th>Bonus Level</th>
                    <th>Username</th>
                    <th class="right">Plan Invest</th>
                    <th class="right">Avail. Date</th>
                    <th class="right">Potential</th>
                </tr>

       <?php foreach ($fbl as $firstb){ ?>
                <tr>
                    <td data-th="Level">
                        1
                    </td>
                    <td data-th="Price">
                        <?php echo $firstb->username; ?>
                    </td>
                    <td data-th="Max. Referrals" class="right">
                        <?php echo money($firstb->amount); ?>
                    </td>
                    <td data-th="Referrals" class="right">
                        <?php echo $firstb->date_of_gh; ?>
                    </td>

                    <td data-th="Potential" class="right">
                        <?php echo money($firstb->amount * 0.05); ?>
                    </td>
                </tr>
                <?php } ?>

                <?php foreach ($sbl as $secondb){ ?>
                <tr>
                    <td data-th="Level">
                        2
                    </td>
                    <td data-th="Price">
                        <?php echo $secondb->username; ?>
                    </td>
                    <td data-th="Max. Referrals" class="right">
                        <?php echo money($secondb->amount); ?>
                    </td>
                    <td data-th="Referrals" class="right">
                        <?php echo $secondb->date_of_gh; ?>
                    </td>

                    <td data-th="Potential" class="right">
                        <?php echo money($secondb->amount * 0.025); ?>
                    </td>
                </tr>
                <?php } ?>


                </tbody></table>

        </div>

    </div>
</div>
