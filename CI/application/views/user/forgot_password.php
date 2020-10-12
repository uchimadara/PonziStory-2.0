<h2>Forgot username or password</h2>
<div id="loginForm" class="loginForm formContainer">

<?= form_open(site_url('user/forgot_password'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'pwFrm')); ?>
<p>
    Enter your email address and we'll send you a link to reset your password.
</p>
<br /><br />
<div class="form-group">
    <label for="email">Email Address</label>
    <?php echo form_error('email'); ?>
    <input class="form-control" type="text" id="email" name="email" value=""/>
</div>
<!--<div class="form-group">-->
<!--    Enter the sum of the two numbers-->
<!--</div>-->
<!--<div class="form-group">-->
<!--    <img src="--><?//= SITE_ADDRESS ?><!--turing_test.jpg" class="turingTest" height="40" width="100"/> =-->
<!--    <input type="text" id="sum" name="sum" size="3" maxlength="2"/><label for="sum"></label>-->
<!--</div>-->

    <div class="formBottom">
        <input class="btn btn-default" type="submit" value="Submit"/>
    </div>
</form>
</div>