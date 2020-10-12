<div class="formContainer" id="changeCountryFrm">
    <?= form_open(site_url('member/change_names'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeCtryFrm')); ?>
    <div class="clearFix">
        <div class="loginPart">
            <div><label>Current First/Last Name</label> <strong><?= $userData->first_name.' '.$userData->last_name ?></strong>
            </div>
            <div class="form-group">
                <label for="first_name">New First Name</label>
                <input class="form-control input-sm" type="text" id="first_name" name="first_name"/>
            </div>
            <div class="form-group">
                <label for="last_name">New Last Name</label>
                <input class="form-control input-sm" type="text" id="last_name" name="last_name"/>
            </div>
            <div class="form-group">
                <label for="secret_answer">Security Answer: <?= $userData->secret_question ?></label>
                <input class="form-control input-sm" type="text" value="" name="secret_answer" id="secret_answer"/>
            </div>
        </div>
    </div>
    <div class="formBottom">
        <input class="btn btn-alt m-r-5" type="submit" value="SAVE"/>
    </div>
    </form>
</div>