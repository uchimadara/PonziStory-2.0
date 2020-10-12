<div class="formContainer" id="changePasswordFrm">
    <?= form_open(site_url('member/change_secret'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changePwdFrm')); ?>
    <div class="clearFix">
            <div class="form-group">
                <label for="secret_question">New Secret Question</label>
                <input class="form-control input-sm" type="text" value="" name="secret_question" id="secret_question"/>
            </div>
            <div class="form-group">
                <label for="secret_answer">New Secret Answer</label>
                <input class="form-control input-sm" type="text" id="secret_answer" name="secret_answer"/>
            </div>
            <div class="form-group">
                <label for="oldpass">Current Password</label>
                <input class="form-control input-sm" type="password" id="passwd" name="passwd"/>
            </div>
    </div>
    <div class="formBottom">
        <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
    </div>
    </form>
</div>