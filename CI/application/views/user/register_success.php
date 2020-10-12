
<h2>Your account has been created!</h2>
<? if( ACTIVATION_EMAIL ) { ?>
<p>
    <b>Congratulations <?= $username ?>! (<?= $email ?>)
        <br/>You have completed the first step of the signup process.</b>
</p>
    <p>
        To ACTIVATE YOUR ACCOUNT:
    </p>
    <p><strong style="color: orangered">1. Click the link sent to your email (Check spam folder too)</strong></p>
    <p><strong style="color: #0E790E">OR</strong></p>
    <p><strong style="color: orangered">2. An SMS CODE was sent to your phone, Fill it below and Click Activate and <b style="color: red"> Click Login</b> </strong></p>




    <?= form_open(site_url('user/smsactivation'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'loginFrm')); ?>
    <input type="hidden" id="username" name="username" value="<?php echo $username ?>"/>

    <div class="form-group col-md-12">

        <label for="username">SMS Code</label>
        <?php echo form_error('smscode'); ?>
        <input class="form-control" type="text" id="smscode" placeholder="Enter SMS Code" name="smscode" />
    </div>

    <div class="formBottom">
        <input class="btn btn-dark btn-sm" type="submit" value="Verify"/>
        <br/><br/>

    </div>

    </form>


    <p>
        If you need any assistance, please submit a support ticket or <strong style="color: #0E790E">Contact Livechat below</strong>.
    </p>
    <p>
        After you have clicked the confirmation link and logged in, visit all areas of your new account to familiarize yourself with our services.
    </p>
    
    
<? } else { ?>
<p>
    <b>Congratulations <?= $username ?>!
        <br/>You have completed the registration process.</b> You can now <a href="<?=SITE_ADDRESS?>login">log in</a>.
    <br/>
    <br/>
    <a href="<?= SITE_ADDRESS ?>login" class="btn btn-dark btn-sm">Log In</a>
</p>
<? } ?>