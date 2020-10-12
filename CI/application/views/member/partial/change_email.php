<? if (empty($userData->secret_question)) { ?>

    <div class="alert alert-warning">You need to set up your secret question and answer before you can change your email.</div>

<? } else { ?>

    <div class="formContainer" id="changeEmailFrm">
        <?= form_open(site_url('member/change_email'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeEmlFrm')); ?>
        <div class="clearFix">
            <div class="loginPart">
                <div class="form-group"><label>Current Email Address</label> <strong><?= $userData->email ?></strong></div>
                <div class="form-group">
                    <label for="email">New Email Address</label>
                    <input class="form-control input-sm" type="text" id="email" name="email"/>
                </div>
                <div class="form-group">
                    <label for="secret_answer"><?= $userData->secret_question ?></label>
                    <input class="form-control input-sm" type="text" value="" name="secret_answer" id="secret_answer"/>
                </div>
            </div>
        </div>
        <div class="formBottom">
            <p>Confirmation e-mail will be sent to new e-mail address.</p>
            <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
        </div>
        </form>
    </div>

<? } ?>
