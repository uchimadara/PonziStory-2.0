<h2 style="text-align: center;color: red;font-weight: bold">DISCLAIMER: THIS PLATFORM IS NOT RELATED TO Federal Govt. Scheme!!! </h2>
<h2 style="text-align: center;color: red;font-weight: bold">SHUN GREED!! NO MULTPLE ACCOUNT </h2>
<h2>SIGN UP</h2>
<div id="registerForm" class=" col-lg-12 col-md-6 col-sm-8 col-xs-12">
    <div class="formContainer register">
        <p class="formError" id="notice"></p>
        <?= form_open('', array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'regFrm')); ?>
        <input type="hidden" name="referrer_id" value="<?=$sponsor->id?>" />
        <input type="hidden" name="sponsor_id" value="<?= isset($origSponsor) ? $origSponsor->id : $sponsor->id ?>"/>

        <div class="clearFix">
            <div class="loginPart">
                <? if (isset($sponsor)) { ?>
                    <? if (isset($origSponsor)) { ?>
                        <p>You were invited by <b><?= $origSponsor->first_name.' '.$origSponsor->last_name ?></b>
                            who has a maximum number of referrals. Our system has assigned
                            <b><?= $sponsor->first_name.' '.$sponsor->last_name ?></b> as your new sponsor.
                        </p>
                    <? } else { ?>
                        <p>You were invited by <b style="color: red"><?= $sponsor->first_name.' '.$sponsor->last_name ?></b>
                        </p>
                    <? } ?>
                <? } ?>
                
                <div class="form-group">
                    <label for="username">Username</label><input type="text" class="form-control" id="username" name="username" value="<?= set_value('username') ?>"/>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email"  <?= isset($email) ? 'value="'.$email.'" readonly="readonly"' : '' ?>/>
                    <small style="color:orangered">Email Confirmation will be sent</small>

                </div>
                <div class="form-group"><label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" />
                </div>
                <div class="form-group">
                    <label for="passconf">Confirm Password</label><input class="form-control"  type="password" id="passconf" name="passconf"/>
                </div>

                <? if(REGISTER_FIELD_NAMES) { ?>
                <div class="form-group">
                    <label for="first_name">First Name: </label>
                    <input type="text" class="form-control " name="first_name" id="first_name" <?= isset($first_name) ? 'value="'.$first_name.'"' : ''?>/>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control input" name="last_name" id="last_name" <?= isset($last_name) ? 'value="'.$last_name.'"' : '' ?>/>
                </div>
                <? } ?>

                <? if(REGISTER_FIELD_ADDRESS) { ?>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control input" name="address" id="address" />
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" class="form-control input" name="city" id="city" />
                </div>
                <div class="form-group">
                    <label for="city">Region / State:</label>
                    <input type="text" class="form-control input" name="state" id="state" />
                </div>
                <div class="form-group">
                    <label for="city">Postcode / Zip:</label>
                    <input type="text" class="form-control input" name="postal_code" id="postal_code" />
                </div>
                <? } ?>

                <? if(REGISTER_FIELD_COUNTRY) { ?>
                <div class="form-group">
                    <label for="country_id">Location</label>
                    <?= form_dropdown("country_id", $this->picklist->select_values('country_list'), $this->data->country, ' class="form-control"') ?>
                </div>
                <? } ?>

                <? if(REGISTER_FIELD_PHONE) { ?>
                <div class="form-group">
                    <h2 style="color:red">To register successfully and receive our SMS, Send the Code below, Enter the phone Number and click create account</h2></p>
                    <p><small><b style="color: darkgreen">ETISALAT:</b> Text START to 2442 on your ETISALAT number to receive SMS.</small></p>
                    <p><small><b style="color: darkorange">MTN:</b> Text ALLOW to 2442 on your MTN number to receive SMS.</small></p>
                    <p><small><b style="color: green">GLO:</b> Text CANCEL to 2442 on your GLO number to receive SMS.</small></p>
                    <p><small><b style="color: red">AIRTEL:</b> Text ALLOW to 2442 on your AIRTEL number to receive SMS.</small></p>
                    <label for="city">Phone number:</label>
                    <input type="text" class="form-control input" placeholder="08034567890"  name="phone" id="phone" />
                    <small style="color:orangered">SMS Confirmation will be sent (<a style="color: green" href="https://tdm.nghelpers.com/news/article/HOW-TO-RECEIVE-SMS-NOTIFICATION-FROM-tradermoni">Click here to be sure if you're not on DND</a> )</small>
                </div>
                <? } ?>

<!--            --><?php //var_dump(ENVIRONMENT ."-". REGISTER_CAPTCHA ) ?>
<!--            --><?php //var_dump(dirname(__FILE__) ) ?>

<!--                <div class="form-group">-->
<!--                    <label for="secret_question">Security Question*</label><input type="text" class="form-control" id="secret_question" name="secret_question"/>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <label for="secret_answer">Security Answer*</label><input type="text" class="form-control" id="secret_answer" name="secret_answer"/>-->
<!--                </div>-->
<!--                <div class="securityQuestion">* Used to make changes to your account.</div>-->
                <? if (ENVIRONMENT == 'production' && REGISTER_CAPTCHA) { // ?>


                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>"></div>
                </div>
                <script src='https://www.google.com/recaptcha/api.js'></script>

                <? } ?>
            </div>
        </div>
        <div class="clear"></div>
        <p class="mTop20">
            Clicking the button below signifies you have read and agree to our terms of service (TOS).</p>
        <div class="formBottom">

            <input class="btn btn-dark btn-sm" type="submit" value="Create Account"/>
        </div>
        </form>
    </div>

</div>
<div class="clear"></div>
