<h1>Account Investment</h1>
<?php //if(!$testi >=  $member_level - 1) {  ?>
<!--    <h1 style="color: red">PLEASE ADD YOUR TESTIMONY BEFORE UPGRADING (JUST A MINUTE)</h1>-->
<!--    <br>-->
<?php foreach ($pending as $pend){ ?>
<a href="#" onclick="toggle_visibility('<?php echo $pend->id ?>');">>  <button type="button" style="line-height: 50px; font-size: 36px;font-weight: bold"  class="btn btn-warning btn-md">MAKE INVESTMENT OF <?php echo $pend->amount." TO ".ucwords($pend->username) ?> </button></a>
<br>




<!--     <a href="--><?//= SITE_ADDRESS ?><!--back_office/testimonials" class="btn btn-alt m-b-5" style="font-size: 25px">Testimonials</a>-->



    <div class="tile p-10">
        <div id="upgradeForm">
            <div class="currentAccount">


                <?php if (isset($onetimepayment)){ ?>

                    <h3 style="color: #0E790E"> ONE TIME PAYMENT </h3>
                <?php } ?>
                <br/>
            </div>
        </div>
    </div>
    <? if (PRELAUNCH_UPGRADE == 0 && LAUNCH_TIME > now()) { ?>
        <div class="alert alert-warning">Upgrade disabled till launch.</div>
        <?php var_dump(intval($upgrade->code)); ?>


    <? } else { ?>
        <div class="col-lg-12" id="<?php echo $pend->id ?>" style="display: none">

        <? if ($userData->account_level == 0) { ?>
            <div class="alert alert-danger">
                <b>IMPORTANT!</b> Make sure you call this member to be sure and make the correct payment as shown below.
                If the member refuses to pay or his/her phone number is not going through, then send a support ticket.
            </div>
        <? } ?>
        <? if (isset($instructions)) {
           // echo $instructions;
        } ?>

        <h2 class="tile-title">STEP 1:  Pay the exact amount shown below in the person's account shown below</h2>

        <? if (isset($origSponsor)) { ?>

        <div class="col-md-12">
            <div class="alert alert-warning left">
                <i class="fa fa-info-circle blue fs18" aria-hidden="true"></i> &nbsp;
                Please be advised that your original sponsor, <?=$origSponsor->first_name.' '.$origSponsor->last_name?>, already has
                the maximum number of upgraded referrals in their front line. You have spilled down and are now assigned to a different
                sponsor (shown below), but are still in your original sponsor's downline.
            </div>
        </div>

        <? } ?>
        <div class="memberSponsor col-md-12">

            <div class="col-md-4 center fs16">
                <? if ($payee->settings->show_avatar) { ?>
                    <img src="<?= avatar($payee->avatar) ?>"/> <br/>
                <? } ?>
                <b><?= $pend->username ?></b><br/>
                <?= $pend->first_name ?> <?= $pend->last_name ?>
                <div class="stars mTop5">
                    <? for ($i = 1; $i <= $pend->account_level; $i++) { ?>
                        <i class="fa fa-star fs10 <? if ($i == 1) echo 'silver'; ?>" aria-hidden="true"></i>
                        <? if ($i == 5) echo '<br />'; ?>
                    <? } ?>
                </div>

            </div>
            <div class="col-md-8 ">
                <h3>Contact Me</h3>
                <? if ($payee->settings->show_email) { ?>
                    <a href="mailto:<?= $pend->email ?>"><img src="<?= asset('images/social/email.png') ?>" style="width: 36px;"/> <?= $payee->email ?>
                    </a><br/>
                <? } ?>
                <? if ($payee->settings->show_phone && !empty($pend->phone)) { ?>
                    <img src="<?= asset('images/social/phone.png') ?>" style="width: 33px;"/> <?= $pend->phone ?><br/>
                <? } ?>
                <div class="m-t-5">
                    <? if (isset($payee->settings->skype_id) && $payee->settings->show_skype == 1) { ?>
                        <a href="skype:<?= $payee->settings->skype_id ?>">
                            <img src="<?= asset('images/social/skype.png') ?>" style="width: 34px;"/>
                        </a> &nbsp;

                    <? } ?>

                    <? if (!empty($payee->socialList)) { ?>
                        <? foreach ($payee->socialList as $social) { ?>

                            <a href="<?= $social->link ?>" target="_blank">
                                <img src="<?= asset("images/social/".strtolower(str_replace('+', '-plus', $social->name)).".png") ?>" width="30px"/>
                            </a> &nbsp;

                        <? } ?>
                    <? } ?>

                </div>
            </div>

        </div>
        <div class="col-md-12">
            <h4 style="border:2px solid rgb(99, 227, 99); padding: 5px;">
                <span class="red">IMPORTANT:</span> Send <b><?=money($pend->amount)?> ONLY</b> to the <b><?= $pend->method_name?></b> wallet listed below
                &nbsp; <i class="fa fa-arrow-down red fs18" aria-hidden="true"></i>
            </h4>


            <div class="col-lg-11 center m-t-11">


                    <div id="memberSummary">

                        <table class="rwd-table">
                            <tbody><tr>
                                <th>Bank</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                                <th>Account Type</th>
                                <th>#</th>

                            </tr>
                            <tr>


                                <td data-th="Bank">
                                    <?= $pend->note ?>          </td>
                                <td data-th="Account Name">
                                    <?= $pend->method_name ?>        </td>
                                <td data-th="Account Number">
                                    <?= $pend->account ?>
                                </td>
                                <td data-th="Referrals" >
                                    <?= $pend->payment_code ?>
                                </td>
               <td data-th="#" style="color: red">If This Member has no payment details or wrong details, Contact the person or inform the Livechat support immediately </td>

                            </tr>

                            </tbody></table>
                    </div>



            </div>
<!--            <div class="col-lg-6 center" style="margin-top:-10px;">-->
<!--                <img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=bitcoin:--><?//= $balance->account ?><!--?amount=--><?//= $upgrade->price ?><!--&message=donation"/>-->
<!---->
<!--            </div>-->

        </div>

        <div class="clear"></div>
        <h2 class="tile-title m-t-20"> STEP 2: Upload Your POP below</h2>
        <? if (isset($step2instructions)) {
            echo $step2instructions;
        } ?>


            <div class="col-md-12 formContainer" style="margin-bottom: 20px;border-bottom: dashed #0A263C medium ">
                <div class="fs18 underline">PAYMENT AMOUNT (NGN) = <?=money($pend->amount)?> <span class="hidden" id="price"><?=$upgrade->price?></span></div>

                <form name="Form" enctype="multipart/form-data" action="<?= SITE_ADDRESS ?>member/upload_pop" method="post" >
                    <input type="hidden" name="payee_user_id" value="<?= $pend->payee_user_id ?>"/>
                    <input type="hidden" name="level" value="<?=$upgradeLevel?>" />
                    <input type="hidden" name="amount" value="<?=$upgrade->price?>" />
                    <input type="hidden" name="somabi" value="<?=$pend->id?>" />
                    <input type="hidden" name="pstd" value="<?=$pend->ph_id?>" />
                    <input type="hidden" name="gstd" value="<?=$pend->gh_id?>" />

                    <!--                <div class="form-group">-->
                    <!--                    <label for="from_account">Your Bitcoin Wallet You Sent From</label>-->
                    <!--                    <input class="form-control" type="text" maxlength="100" name="from_account" id="from_account" value=""/>-->
                    <!--                </div>-->
                    <!--                <div class="form-group">-->
                    <!--                    <label for="transaction_id">Transaction Hash ID</label>-->
                    <!--                    <input class="form-control" type="text" maxlength="100" name="transaction_id" id="transaction_id" value=""/>-->
                    <!--                </div>-->
                    <!--                <div class="form-group">-->
                    <!--                    <label for="amount">Exact Amount Sent</label>-->
                    <!--                    <input class="form-control" type="text" maxlength="100" name="amount" id="amount" value="" placeholder="Enter amount of Bitcoins" onkeyup="maskAmount(this);"/>-->
                    <!--                </div>-->
                    <div>
                        <h2 style="color: red">FAKE POP IS A CAPITAL OFFENSE: BE WARNED!!! </h2>
                        <div class="alert info-error m-t-10">
                            <span style="color: red;font-weight: bold"> Maximum size: (700KB) | Allowed Type:gif|jpg|jpeg|png</span>
                        </div>
                        <style>
                            img{
                                max-width:180px;
                            }
                            input[type=file]{
                                padding:10px;
                                background:#2d2d2d;}
                        </style>


                        <div class="form-group fileUpload" id="imageUpload">
                            <label for="banner" class="uploadLabel">
                                <i class="fa fa-cloud-upload"></i> Upload your image</label><br/>

                            <div id="fileSelect">
                                <input class="form-control input-sm upload" type="file" name="proof_img" id="banner" size="20" onchange="readURL(this);" />
                            </div>
                            <img id="blah" src="http://placehold.it/180" alt="your image" />
                        </div>

                    </div>

                    <div class="submit">
                        <input type="submit" style="margin-bottom: 20px" value="Submit" class="btn btn-alt"/>
                    </div>
                </form>
            </div>



        </div>



    <? } ?>


<?php } ?>





<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script type="text/javascript">
    <!--
    function toggle_visibility(id) {
        var e = document.getElementById(id);
        if(e.style.display == 'block')
            e.style.display = 'none';
        else
            e.style.display = 'block';
    }
    //-->
</script>