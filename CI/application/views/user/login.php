
<h2>Sign in to your account</h2>
<div id="loginForm" class="loginForm formContainer col-lg-12 col-md-6 col-sm-8 col-xs-12">
    <? if ($this->session->flashdata('success')) { ?>
        <p style="color: #0E790E">
            Your password has been reset. Check your email and use the new password sent to you.
        </p>
    <? } ?>
    <? if ($errMsg = $this->session->flashdata('error')) { ?>
        <p style="color:red">
            <?= $errMsg ?>
        </p>
    <? } ?>

    <?= form_open(site_url('user/login'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'loginFrm')); ?>
    <input type="hidden" id="remember" name="remember" value="1"/>

    <div class="form-group col-md-12">

        <label for="username">Username</label>
        <?php echo form_error('username'); ?>
        <input class="form-control" type="text" id="username" placeholder="Enter username" name="username" value="<?= set_value('username') ?>"/>
    </div>
    <div class="form-group col-md-12">

        <label for="password">Password</label>
        <?php echo form_error('password'); ?>
        <input class="form-control" type="password" id="password" placeholder="Enter password" name="password"/>
    </div>
    <? if (ENVIRONMENT == 'production' && LOGIN_CAPTCHA) { // ?>
    <div class="form-group col-md-12">
        <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>"></div>

    </div>



    <? } ?>
    <div class="formBottom">
        <input class="btn btn-dark btn-sm" type="submit" value="Sign In"/>
        <br/><br/>

    <span class="links">
    <? if (ACTIVATION_EMAIL == 1) { ?>
        <?= anchor('user/resend', 'Resend activation email', 'class="showResend"') ?><br/>
    <? } ?>
    <?= anchor('user/forgot_password', 'Forgot username or password?', 'class="text-theme-colored font-weight-800 font-14"') ?>
    </span>

    </div>

    </form>
</div>
<div class="clear"></div>


