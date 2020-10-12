<div class="formContainer" id="changeCountryFrm">
    <?= form_open(site_url('member/change_phone'), array('method' => 'post', 'class' => 'frm_ajax', 'id' => 'changeCtryFrm')); ?>
    <div class="clearFix">
        <div class="loginPart">
            <div><label>Current Phone</label> <strong><?= $userData->phone ?></strong>
            </div>
            <div class="form-group">
                <label for="phone">New Phone</label>
                <input class="form-control input-sm" type="text" id="phone" name="phone"/>
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