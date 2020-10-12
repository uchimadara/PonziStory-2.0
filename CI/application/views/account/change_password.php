<div class="container" id="changePasswordFrm">
    <h1>Change your password</h1>
    <?=form_open(site_url('member/change_password'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changePwdFrm')); ?>
        <div class="clearFix">
            <div class="loginPart">
                <div><label for="oldpass">Current Password</label> <input type="password" id="oldpass" name="oldpass" /></div>
                <div><label for="password">New Password</label> <input type="password" id="password" name="password" /></div>
                <div><label for="passconf">Confirm New Password</label> <input type="password" id="passconf" name="passconf" /></div>
                <div><label for="secret_answer"><?= $userData->secret_question ?></label>

                    <input type="text" value="" name="secret_answer" id="secret_answer"/></div>
            </div>
        </div>
        <div class="bottom">
            <input class="btn" type="submit" value="SAVE"/>
        </div>
    </form>
</div>