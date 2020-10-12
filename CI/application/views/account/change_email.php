<div class="container" id="changeEmailFrm">
    <h1>Change your email</h1>
    <?=form_open(site_url('member/change_email'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeEmlFrm')); ?>
        <div class="clearFix">
            <div class="loginPart">
                <div><label>Current Email Address</label> <strong><?=$email?></strong></div>
                <div><label for="email">New Email Address</label> <input type="text" id="email" name="email" /></div>
            </div>
        </div>
        <div class="bottom">
            <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
        </div>
    </form>
</div>