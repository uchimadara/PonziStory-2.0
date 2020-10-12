<? if (empty($userData->secret_question)) { ?>

    <div class="alert alert-warning">You need to set up your secret question and answer before you can change your password.</div>

<? } else { ?>

    <div class="formContainer" id="changePasswordFrm">
        <?= form_open(site_url('member/change_password'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changePwdFrm')); ?>
        <div class="clearFix">
            <div class="loginPart">
                <div class="form-group">
                    <label for="oldpass">Current Password</label>
                    <input class="form-control input-sm" type="password" id="oldpass" name="oldpass"/>
                </div>
                <div class="form-group"><label for="password">New Password</label>
                    <input class="form-control input-sm" type="password" id="password" name="password"/></div>
                <div class="form-group"><label for="passconf">Confirm New Password</label>
                    <input class="form-control input-sm" type="password" id="passconf" name="passconf"/></div>
                <div class="form-group">
                    <label for="secret_answer"><?= $userData->secret_question ?></label>
                    <input class="form-control input-sm" type="text" value="" name="secret_answer" id="secret_answer"/>
                </div>
            </div>
        </div>
        <div class="formBottom">
            <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
        </div>
        </form>
    </div>

<? } ?>
